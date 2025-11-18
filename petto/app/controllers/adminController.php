<?php
// app/controllers/AdminController.php

// Inicia a sessão se não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inclui o Model que interage com o Banco de Dados
require_once __DIR__ . '/../models/Admin.php';
    include __DIR__ . "../../views/head.php";

class AdminController
{
    /**
     * Rota principal do Admin (/petto/admin). 
     * Despacha para a ação correta ou para a Dashboard com base no parâmetro 'page'.
     */
    public function index()
    {
        // 1. Verificação de autenticação e autorização
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
            header("Location: /petto/auth/login");
            exit;
        }

        // 2. Roteamento interno por parâmetro 'page'
        $page = $_GET['page'] ?? 'dashboard';

        switch ($page) {
            case 'usuarios':
                $this->usuarios(); 
                break;
            case 'dashboard':
                $this->dashboard(); 
                break;
            default:
                // Página não encontrada (404)
                require_once __DIR__ . '/../views/admin/404.php'; 
                break;
        }
    }
    
    /**
     * Rota da Dashboard principal.
     */
    public function dashboard()
    {
        // Carrega a view da dashboard principal
        require_once __DIR__ . '/../views/admin/index.php';
    }
    
    // ====================================================
    // MÉTODO AUXILIAR: Lidar com o Upload de Arquivo
    // ====================================================
    /**
     * Tenta fazer o upload de um arquivo e retorna o caminho público.
     * @param array $file Array $_FILES['nome_do_campo']
     * @param string|null $current_url URL da foto atual no banco de dados.
     * @return string|null URL pública da nova foto, a URL atual (se não houver novo upload), ou NULL em caso de erro fatal no upload.
     */
    private function handleFileUpload($file, $current_url = null): ?string
    {
        // 1. Retorna a URL atual se não houver um novo arquivo válido sendo enviado
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return $current_url;
        }

        // 2. Define o diretório e o caminho absoluto (ATENÇÃO: CRIE ESSA PASTA E DÊ PERMISSÃO)
        // O caminho aponta para /petto/public/uploads/profile/
        $upload_dir = __DIR__ . '/../../public/uploads/profile/';
        
        // Garante que o diretório exista e tenta criar se necessário
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                error_log("Erro: Não foi possível criar o diretório de upload: " . $upload_dir);
                return $current_url; 
            }
        }

        // 3. Gera um nome de arquivo único
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_filename = uniqid('profile_') . '.' . $ext;
        $destination = $upload_dir . $new_filename;
        
        // 4. Move o arquivo
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // Retorna o caminho público (URL)
            return '/petto/public/uploads/profile/' . $new_filename;
        }
        
        return $current_url; // Falha no move_uploaded_file
    }

    /**
     * Lógica para a página de gerenciamento de usuários (Listagem, Edição, Exclusão).
     * Rota: /petto/admin?page=usuarios
     */
    private function usuarios()
    {
        // ====================================================
        // Lógica de POST (Editar ou Excluir)
        // ====================================================
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
            $acao = $_POST['acao'];
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT); 
            $role = $_POST['role'] ?? '';
            $result = ['error' => 'Ação inválida.'];

            if ($id) {
                if ($acao === 'excluir') {
                    if ($role === 'tutor') {
                        $result = Admin::deleteTutor($id); 
                    } elseif ($role === 'veterinario' || $role === 'vet') {
                        $result = Admin::deleteVeterinario($id);
                    }
                } elseif ($acao === 'editar') {
                    // Prepara os dados para o Model
                    $data = $_POST;
                    unset($data['acao'], $data['role'], $data['id']);

                    // ====================================================
                    // TRATAMENTO DO UPLOAD DE FOTO
                    // ====================================================
                    // Para Veterinários, o user_id virá do campo hidden que você adicionou.
                    $current_foto_url = $_POST['foto_url_current'] ?? null;
                    $foto_upload_data = $_FILES['foto_file'] ?? null;
                    
                    // Tenta processar o upload
                    $new_foto_url = $this->handleFileUpload($foto_upload_data, $current_foto_url);
                    
                    // Se o upload ou a retenção da URL for bem-sucedida, a nova URL é adicionada aos dados
                    if ($new_foto_url !== null) {
                        $data['foto_url'] = $new_foto_url;
                    } 
                    
                    // Tratamento de erro de tamanho máximo de arquivo (excede o limite do php.ini)
                    if ($foto_upload_data && $foto_upload_data['error'] === UPLOAD_ERR_INI_SIZE) {
                        $_SESSION['flash']['error'] = 'Erro: O arquivo de foto excede o tamanho máximo permitido.';
                        header('Location: /petto/admin?page=usuarios'); 
                        exit;
                    }
                    
                    // Executa a atualização no Model
                    if ($role === 'tutor') {
                        // Tutor usa o 'id' principal para o update
                        $result = Admin::updateTutor($id, $data);
                    } elseif ($role === 'veterinario' || $role === 'vet') {
                        // Veterinário usa o 'id' para o update de vet e 'user_id' (em $data) para o update de usuarios
                        $result = Admin::updateVeterinario($id, $data);
                    }
                }
            }

            // Define a mensagem flash na sessão
            if (isset($result['success'])) {
                $_SESSION['flash']['success'] = $result['success'];
            } elseif (isset($result['error'])) {
                $_SESSION['flash']['error'] = $result['error'];
            }

            // Redireciona para evitar reenvio do formulário
            header('Location: /petto/admin?page=usuarios'); 
            exit;
        }

        // ====================================================
        // Lógica de GET (Listar Usuários)
        // ====================================================
        
        $tutores = Admin::getAllTutors();
        $veterinarios = Admin::getAllVeterinarios(); 

        require_once __DIR__ . '/../views/admin/usuarios.php';
    }
}