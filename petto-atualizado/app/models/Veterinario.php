<?php
// app/models/Veterinario.php
require_once __DIR__ . '/../../config/database.php';

class Veterinario {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection(); 
    }

    /**
     * Registra um novo veterinário na tabela 'veterinarios'.
     */
    public function register($data, $user_id) {
        try {
            // A consulta abaixo contém apenas as colunas que presumimos existir no seu banco.
            $stmt = $this->conn->prepare("
                INSERT INTO veterinarios (
                    nome, nome_clinica, tempo_experiencia, telefone, email, endereco,
                    cep_clinica, bairro_clinica, numero_clinica, cpf, crmv, user_id
                ) VALUES (
                    :nome, :nome_clinica, :tempo_experiencia, :telefone, :email, :endereco,
                    :cep_clinica, :bairro_clinica, :numero_clinica, :cpf, :crmv, :user_id
                )
            ");
    
            return $stmt->execute([
                ':nome' => $data['nome'] ?? NULL,
                ':nome_clinica' => $data['nome_clinica'] ?? NULL,
                ':tempo_experiencia' => $data['tempo_experiencia'] ?? NULL,
                ':telefone' => $data['telefone'] ?? NULL,
                ':email' => $data['email'] ?? NULL,
                ':endereco' => $data['endereco'] ?? NULL, // Endereço da clínica (Rua/Av)
                ':cep_clinica' => $data['cep_clinica'] ?? NULL,
                ':bairro_clinica' => $data['bairro_clinica'] ?? NULL,
                ':numero_clinica' => $data['numero_clinica'] ?? NULL,
                ':cpf' => $data['cpf'] ?? NULL,
                ':crmv' => $data['crmv'] ?? NULL,
                ':user_id' => $user_id
            ]);
        } catch (PDOException $e) {
             throw new Exception("Erro ao registrar veterinário: " . $e->getMessage());
        }
    }
}