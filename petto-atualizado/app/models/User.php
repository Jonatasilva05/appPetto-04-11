<?php
// app/models/User.php
require_once __DIR__ . '/../../config/database.php';

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }
    
    /**
     * EXPÕE a conexão PDO para gerenciamento de transações no AuthController.
     */
    public function getPdo() {
        return $this->pdo;
    }

    public function login($email, $senha) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($senha, $user['senha'])) {
            return $user;
        }
        return false;
    }

    public function registerTutor($data) {
        try {
            $stmt_check = $this->pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt_check->execute([$data['email']]);
            if ($stmt_check->fetch()) {
                return ['error' => 'O e-mail fornecido já está em uso.'];
            }
            
            $senhaHash = password_hash($data['senha'], PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("
                INSERT INTO usuarios 
                (email, senha, nome, telefone, endereco, foto_url, pet_primario, cor_favorita, role) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'tutor')
            ");
            $stmt->execute([
                $data['email'] ?? '',
                $senhaHash,
                $data['nome'] ?? '',
                $data['telefone'] ?? '',
                $data['endereco'] ?? '',
                $data['foto_url'] ?? '', 
                $data['pet_primario'] ?? '',
                $data['cor_favorita'] ?? ''
            ]);
            
            return ['success' => true];
        } catch (PDOException $e) {
             throw new Exception("Erro de banco de dados ao registrar tutor: " . $e->getMessage());
        }
    }
}