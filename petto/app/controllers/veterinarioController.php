<?php
// app/controllers/VeterinarioController.php

// Inicia a sessão se não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inclui o Model que interage com o Banco de Dados
require_once __DIR__ . '/../models/Profissional.php';

class VeterinarioController
{
    /**
     * Rota principal do Veterinário (/petto/veterinario). 
     * Despacha para a ação correta ou para a Dashboard com base no parâmetro 'page'.
     */
    public function index()
    {
        // 1. Verificação de autenticação e autorização
        if (!isset($_SESSION['user']) || !in_array(($_SESSION['user']['role'] ?? ''), ['veterinario', 'vet'])) {
            header("Location: /petto/auth/login");
            exit;
        }

        // 2. Roteamento interno por parâmetro 'page'
        $page = $_GET['page'] ?? 'index';

        switch ($page) {
            case 'pets':
                $this->pets(); 
                break;
            case 'prontuario':
                $this->prontuario(); 
                break;
            case 'index':
            default:
                $this->dashboard(); 
                break;
        }
    }
    
    /**
     * Exibe a Dashboard do Veterinário.
     */
    public function dashboard()
    {
        $current_page = 'index';
        // Lógica para obter dados da dashboard (a ser implementada, se necessário)
        require_once __DIR__ . '/../views/veterinario/index.php'; 
    }

    /**
     * Exibe a lista de pacientes do Veterinário.
     */
    public function pets()
    {
        $veterinarioData = Profissional::getVeterinarioDataByUserId($_SESSION['user']['id']);
        $veterinarioId = $veterinarioData['id_veterinario'] ?? 0;
        
        $pets = Profissional::getAllPets($veterinarioId);

        $current_page = 'pets';
        require_once __DIR__ . '/../views/veterinario/pets.php'; 
    }

    /**
     * Exibe o prontuário de um pet específico e processa os formulários POST.
     */
    public function prontuario()
    {
        $veterinarioData = Profissional::getVeterinarioDataByUserId($_SESSION['user']['id']);
        $veterinarioId = $veterinarioData['id_veterinario'] ?? 0;
        $id_pet = (int) ($_GET['id_pet'] ?? 0);

        if ($id_pet === 0) {
            header("Location: /petto/veterinario?page=pets");
            exit;
        }

        $pet_details = Profissional::getPetDetails($id_pet);
        $historico = Profissional::getHistorico($id_pet);
        $result = ['error' => ''];
        
        // 1. Lógica de POST (Processamento dos Formulários)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $data = $_POST;
            $data['id_pet'] = $id_pet; // Garante que o ID do pet seja usado

            switch ($data['action']) {
                case 'novo_prontuario':
                    // Verifica campos obrigatórios da consulta (Diagnóstico é NOT NULL no Model)
                    if (!empty($data['diagnostico']) && !empty($data['data_consulta'])) {
                        $result = Profissional::saveProntuario($data, $veterinarioId);
                    } else {
                         $result['error'] = 'Diagnóstico e Data da Consulta são obrigatórios.';
                    }
                    break;
                case 'add_vacina':
                    // Verifica campos obrigatórios da vacina
                    if (!empty($data['nome_vacina']) && !empty($data['data_aplicacao']) && !empty($data['proxima_aplicacao'])) {
                        $result = Profissional::saveVacina($data);
                    } else {
                        $result['error'] = 'Nome da Vacina, Data de Aplicação e Próxima Aplicação são obrigatórios.';
                    }
                    break;
                case 'add_medicamento':
                    if (!empty($data['nome_medicamento'])) {
                        $result = Profissional::saveMedicamento($data);
                    } else {
                        $result['error'] = 'Nome do Medicamento é obrigatório.';
                    }
                    break;
            }

            // Define a mensagem flash e redireciona para o mesmo prontuário
            if (isset($result['success'])) {
                $_SESSION['flash']['success'] = $result['success'];
            } elseif (isset($result['error'])) {
                $_SESSION['flash']['error'] = $result['error'];
            }
            
            // Redireciona para evitar reenvio do formulário e atualizar histórico
            header("Location: /petto/veterinario?page=prontuario&id_pet={$id_pet}");
            exit;
        }

        // 2. Lógica de GET (Exibir a página)
        if (!$pet_details) {
            header("Location: /petto/veterinario?page=pets");
            exit;
        }

        $current_page = 'prontuario';
        require_once __DIR__ . '/../views/veterinario/prontuario.php';
    }
}