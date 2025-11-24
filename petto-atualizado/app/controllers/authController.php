<?php
// app/controllers/authController.php

include __DIR__ . "../../views/head.php";
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Veterinario.php';
// Inclua seu head aqui
// include __DIR__ . "/../views/head.php"; 

class AuthController
{
    // Funçao auxiliar para lidar com o upload de arquivo
    private function handleFileUpload($fileInputName) {
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
            // Caminho para a pasta onde as fotos serão salvas.
            $upload_dir = __DIR__ . '/../../public/uploads/'; 
            
            if (!is_dir($upload_dir)) {
                if (!mkdir($upload_dir, 0777, true)) {
                    return false;
                }
            }
            
            // Garante que o nome do arquivo seja único e seguro, mantendo a extensão
            $file_extension = pathinfo($_FILES[$fileInputName]['name'], PATHINFO_EXTENSION);
            $file_name = uniqid('foto_') . '.' . $file_extension;
            $target_file = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $target_file)) {
                // Retorna o caminho relativo ou URL para salvar no banco de dados
                return '/petto/public/uploads/' . $file_name;
            } else {
                return false;
            }
        }
        return NULL;
    }
    
    /**
     * Função robusta para validar o CPF.
     * @param string $cpf O CPF a ser validado
     * @return bool True se o CPF for válido, False caso contrário
     */
    private function validateCpf($cpf) {
        // Remove caracteres não numéricos
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        // Verifica se tem 11 dígitos
        if (strlen($cpf) !== 11) {
            return false;
        }

        // Verifica se todos os dígitos são iguais (ex: 111.111.111-11)
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Valida 1o digito verificador
        for ($i = 0, $j = 10, $soma = 0; $i < 9; $i++, $j--) {
            $soma += $cpf[$i] * $j;
        }
        $resto = $soma % 11;
        $digito1 = $resto < 2 ? 0 : 11 - $resto;

        if ($cpf[9] != $digito1) {
            return false;
        }

        // Valida 2o digito verificador
        for ($i = 0, $j = 11, $soma = 0; $i < 10; $i++, $j--) {
            $soma += $cpf[$i] * $j;
        }
        $resto = $soma % 11;
        $digito2 = $resto < 2 ? 0 : 11 - $resto;

        if ($cpf[10] != $digito2) {
            return false;
        }

        return true;
    }
    
    public function login()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            $userModel = new User();
            $user = $userModel->login($email, $senha);

            if ($user) {
                
                // === BLOQUEIO DE LOGIN DE TUTOR PELA WEB ===
                if ($user['role'] === 'tutor') {
                    // Limpa a tentativa de sessão para o tutor
                    session_unset();
                    session_destroy();
                    session_start();
                    $_SESSION['erro'] = "Acesse sua conta tutor pelo aplicativo.";
                    header('Location: /petto/auth/login');
                    exit;
                }
                // ===========================================
                
                $_SESSION['user'] = $user;
                if ($user['role'] === 'admin') {
                    header('Location: /petto/admin/dashboard');
                } elseif ($user['role'] === 'veterinario') {
                    header('Location: /petto/veterinario/dashboard');
                } else {
                    // Esta branch nunca será alcançada se o bloqueio acima funcionar
                    header('Location: /petto/tutor/dashboard');
                }
                exit;
            } else {
                $_SESSION['erro'] = "E-mail ou senha incorretos.";
            }
        }
        include __DIR__ . '/../views/auth/login.php';
    }

    // Cadastro de Tutor (Com validações específicas de e-mail e senha)
    public function register_tutor()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $errors = []; // Array para armazenar erros específicos por campo
            
            // Validação de E-mail
            $email = $data['email'] ?? '';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "O formato do e-mail é inválido.";
            }
            
            // Validação de Senha (Mínimo 8 dígitos)
            $senha = $data['senha'] ?? '';
            if (strlen($senha) < 8) {
                $errors['senha'] = "A senha deve ter no mínimo 8 caracteres.";
            }

            // Se houver erros, armazena dados e erros na sessão e redireciona
            if (!empty($errors)) {
                $_SESSION['form_data'] = $_POST;
                $_SESSION['errors'] = $errors; // Armazena erros específicos
                header('Location: /petto/auth/register_tutor');
                exit;
            }
            
            // Continuar com o cadastro se não houver erros
            $data['foto_url'] = $this->handleFileUpload('foto_perfil');

            $userModel = new User();
            try {
                $result = $userModel->registerTutor($data);
            } catch (Exception $e) {
                $result = ['error' => $e->getMessage()];
            }

            if (isset($result['error'])) {
                $_SESSION['form_data'] = $_POST;
                $_SESSION['erro'] = $result['error']; // Erros de DB ou outros genéricos ainda usam 'erro'
                header('Location: /petto/auth/register_tutor');
                exit;
            }

            $_SESSION['sucesso'] = "Cadastro de tutor realizado com sucesso! Faça login.";
            header('Location: /petto/auth/login');
            exit;
        }

        include __DIR__ . '/../views/auth/register_tutor.php';
    }

    // Cadastro de Veterinário (Com validações específicas de e-mail, senha e CPF)
    public function register_vet()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $errors = []; // Array para armazenar erros específicos por campo
            
            // Validação de E-mail
            $email = $data['email'] ?? '';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "O formato do e-mail é inválido.";
            }
            
            // Validação de Senha (Mínimo 8 dígitos)
            $senha = $data['senha'] ?? '';
            if (strlen($senha) < 8) {
                $errors['senha'] = "A senha deve ter no mínimo 8 caracteres.";
            }
            
            // Validação de CPF
            $cpf = $data['cpf'] ?? '';
            if (empty($cpf)) {
                 $errors['cpf'] = "O campo CPF é obrigatório.";
            } elseif (!$this->validateCpf($cpf)) {
                $errors['cpf'] = "O CPF informado é inválido.";
            }
            
            // Se houver erros, armazena dados e erros na sessão e redireciona
            if (!empty($errors)) {
                $_SESSION['form_data'] = $_POST;
                $_SESSION['errors'] = $errors; // Armazena erros específicos
                header('Location: /petto/auth/register_vet');
                exit;
            }

            // Continuar com o cadastro se não houver erros
            $userModel = new User();
            $vetModel = new Veterinario();
            $user_id = null;
            
            // 1. Lidar com o Upload de Arquivo
            $foto_url = $this->handleFileUpload('foto_perfil');
            if ($foto_url === false) {
                 $_SESSION['erro'] = "Erro ao processar o upload da foto. Verifique as permissões do diretório 'public/uploads'.";
                 $_SESSION['form_data'] = $_POST; 
                 header('Location: /petto/auth/register_vet');
                 exit;
            }
            $data['foto_url'] = $foto_url;
            
            try {
                // Iniciar Transação (CORREÇÃO ESSENCIAL)
                $userModel->getPdo()->beginTransaction(); 

                // 2. Inserção na Tabela 'usuarios' (Dados Pessoais + Perfil)
                $senhaHash = password_hash($data['senha'], PASSWORD_DEFAULT);
                $stmt = $userModel->getPdo()->prepare("
                    INSERT INTO usuarios 
                    (email, senha, nome, telefone, endereco, foto_url, pet_primario, cor_favorita, role) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'veterinario')
                ");
                $stmt->execute([
                    $data['email'] ?? '',
                    $senhaHash,
                    $data['nome'] ?? '',
                    $data['telefone'] ?? '',
                    $data['endereco_pessoal'] ?? '', // Endereço Pessoal
                    $data['foto_url'] ?? '',
                    $data['pet_primario'] ?? '',
                    $data['cor_favorita'] ?? ''
                ]);
                
                // Obter o último ID (CORREÇÃO ESSENCIAL)
                $user_id = $userModel->getPdo()->lastInsertId();

                if (!$user_id) {
                    throw new Exception("Falha ao obter o ID do novo usuário.");
                }

                // 3. Inserção na Tabela 'veterinarios' (Dados Profissionais)
                $data['endereco'] = $data['endereco_clinica'] ?? ''; // Mapeia o endereço da clínica para a coluna 'endereco'
                $vetModel->register($data, $user_id);
                
                // Finaliza a transação (CORREÇÃO ESSENCIAL)
                $userModel->getPdo()->commit();
                
                $_SESSION['sucesso'] = "Cadastro de veterinário completo realizado com sucesso! Faça login.";
                header('Location: /petto/auth/login');
                exit;

            } catch (Exception $e) {
                // Em caso de falha, desfaz as operações
                if ($userModel->getPdo()->inTransaction()) {
                    $userModel->getPdo()->rollBack();
                }
                
                $_SESSION['form_data'] = $_POST; 
                $_SESSION['erro'] = "Erro ao finalizar o cadastro completo: " . $e->getMessage(); // Erros de DB ou outros genéricos ainda usam 'erro'
                header('Location: /petto/auth/register_vet');
                exit;
            }
        }

        include __DIR__ . '/../views/auth/register_vet.php';
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /petto/auth/login');
        exit;
    }
}