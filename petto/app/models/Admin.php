<?php
// app/models/Admin.php

require_once __DIR__ . '/../../config/database.php';

class Admin {
    
    private const USER_TABLE = 'usuarios'; 
    private const VET_TABLE = 'veterinarios';

    private static function getDbConnection(): PDO {
        return Database::getConnection(); 
    }

    // ====================================================
    // MÉTODOS DE LEITURA (GET)
    // ====================================================

    /**
     * Obtém todos os tutores cadastrados.
     */
    public static function getAllTutors(): array {
        try {
            $db = self::getDbConnection(); 
            $sql = "SELECT 
                        u.id, u.nome, u.email, u.role, u.telefone, u.endereco, u.foto_url,
                        u.pet_primario, u.cor_favorita
                    FROM " . self::USER_TABLE . " u
                    WHERE u.role = 'tutor'
                    ORDER BY u.nome ASC";
            
            $stmt = $db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erro ao obter tutores: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtém TODOS os veterinários cadastrados (ID principal é id_veterinario).
     */
    public static function getAllVeterinarios(): array {
        try {
            $db = self::getDbConnection(); 
            $sql = "SELECT 
                        v.id_veterinario AS id,  
                        v.nome, v.email, v.telefone, v.endereco, v.user_id,
                        v.cpf, v.crmv, v.nome_clinica, v.tempo_experiencia, 
                        v.cep_clinica, v.bairro_clinica, v.numero_clinica,
                        u.role, 
                        u.foto_url,
                        u.pet_primario, u.cor_favorita
                    FROM " . self::VET_TABLE . " v
                    LEFT JOIN " . self::USER_TABLE . " u ON v.user_id = u.id
                    ORDER BY v.nome ASC";
            
            $stmt = $db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erro ao obter veterinários: " . $e->getMessage());
            return [];
        }
    }
    
    // ====================================================
    // MÉTODOS DE ATUALIZAÇÃO (UPDATE)
    // ====================================================

    /**
     * Atualiza o perfil de um Tutor (APENAS na tabela usuarios).
     */
    public static function updateTutor(?int $id, array $data): array {
        if (!$id) { return ['error' => 'ID de Tutor inválido para atualização.']; }
        
        try {
            $db = self::getDbConnection();
            
            // 'foto_url' está aqui
            $user_fields = ['nome', 'email', 'telefone', 'endereco', 'foto_url', 'pet_primario', 'cor_favorita'];
            $user_updates = []; $user_params = ['id' => $id];

            foreach ($user_fields as $field) { if (isset($data[$field])) { $user_updates[] = "$field = :$field"; $user_params[$field] = $data[$field]; } }
            if (isset($data['senha']) && !empty(trim($data['senha']))) { $user_updates[] = "senha = :senha"; $user_params['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT); }

            if (!empty($user_updates)) {
                $db->prepare("UPDATE " . self::USER_TABLE . " SET " . implode(', ', $user_updates) . " WHERE id = :id")->execute($user_params);
            }

            return ['success' => 'Tutor atualizado com sucesso!'];

        } catch (PDOException $e) {
            error_log("Erro ao atualizar tutor: " . $e->getMessage());
            return ['error' => 'Erro interno ao atualizar tutor.'];
        }
    }

    /**
     * Atualiza o perfil de um Veterinário (tabelas veterinarios e usuarios).
     */
    public static function updateVeterinario(?int $id, array $data): array {
        if (!$id) { return ['error' => 'ID de Veterinário inválido para atualização.']; }

        try {
            $db = self::getDbConnection();
            $db->beginTransaction();

            // 1. Atualizar a tabela de Veterinários (dados profissionais) - Usa o id_veterinario
            $vet_fields = ['nome', 'email', 'telefone', 'endereco', 'cpf', 'crmv', 'nome_clinica', 'tempo_experiencia', 'cep_clinica', 'bairro_clinica', 'numero_clinica'];
            $vet_updates = []; $vet_params = ['id_veterinario' => $id];
            foreach ($vet_fields as $field) { 
                if (isset($data[$field])) { $vet_updates[] = "$field = :$field"; $vet_params[$field] = $data[$field]; } 
            }
            
            if (!empty($vet_updates)) {
                $db->prepare("UPDATE " . self::VET_TABLE . " SET " . implode(', ', $vet_updates) . " WHERE id_veterinario = :id_veterinario")->execute($vet_params);
            }
            
            // 2. Atualizar a tabela de Usuários (foto, senha e campos comuns) - Usa o user_id
            $user_id = $data['user_id'] ?? null; // user_id vem do campo hidden do formulário
            
            if ($user_id) {
                $user_updates = []; $user_params = ['id' => $user_id];
                
                // 'foto_url' está aqui
                $user_updateable_fields = ['foto_url', 'email', 'nome', 'pet_primario', 'cor_favorita'];

                foreach ($user_updateable_fields as $field) {
                    if (isset($data[$field])) { 
                        $user_updates[] = "$field = :$field"; 
                        $user_params[$field] = $data[$field]; 
                    }
                }
                
                // Senha
                if (isset($data['senha']) && !empty(trim($data['senha']))) { 
                    $user_updates[] = "senha = :senha"; 
                    $user_params['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT); 
                }

                if (!empty($user_updates)) {
                    $db->prepare("UPDATE " . self::USER_TABLE . " SET " . implode(', ', $user_updates) . " WHERE id = :id")->execute($user_params);
                }
            }

            $db->commit();
            return ['success' => 'Veterinário atualizado com sucesso!'];

        } catch (PDOException $e) {
            if (isset($db) && $db->inTransaction()) { $db->rollBack(); }
            error_log("Erro ao atualizar veterinário: " . $e->getMessage());
            return ['error' => 'Erro interno ao atualizar veterinário.'];
        }
    }

    // ====================================================
    // MÉTODOS DE EXCLUSÃO (DELETE)
    // ====================================================

    /**
     * Exclui um Tutor (APENAS na tabela usuarios).
     */
    public static function deleteTutor(?int $id): array {
        if (!$id) { return ['error' => 'ID de Tutor inválido para exclusão.']; }

        try {
            $db = self::getDbConnection();

            $stmt_user = $db->prepare("DELETE FROM " . self::USER_TABLE . " WHERE id = :id AND role = 'tutor'");
            $stmt_user->execute(['id' => $id]);

            if ($stmt_user->rowCount() === 0) { return ['error' => 'Usuário Tutor não encontrado para exclusão.']; }
            
            return ['success' => 'Tutor excluído com sucesso!'];

        } catch (PDOException $e) {
            error_log("Erro ao excluir tutor: " . $e->getMessage());
            return ['error' => 'Erro interno ao excluir tutor.'];
        }
    }

    /**
     * Exclui um Veterinário (tabelas veterinarios e usuarios).
     */
    public static function deleteVeterinario(?int $id): array {
        if (!$id) { return ['error' => 'ID de Veterinário inválido para exclusão.']; }

        try {
            $db = self::getDbConnection();
            $db->beginTransaction();

            // 1. Puxar o user_id antes de deletar o registro principal
            $stmt_get_user = $db->prepare("SELECT user_id FROM " . self::VET_TABLE . " WHERE id_veterinario = :id");
            $stmt_get_user->execute(['id' => $id]);
            $vet_data = $stmt_get_user->fetch(PDO::FETCH_ASSOC);
            $user_id = $vet_data['user_id'] ?? null;

            // 2. Excluir o registro da tabela específica (veterinarios) usando id_veterinario
            $db->prepare("DELETE FROM " . self::VET_TABLE . " WHERE id_veterinario = :id")->execute(['id' => $id]);

            // 3. Excluir o registro da tabela de usuários (SE user_id existir)
            if ($user_id) {
                $db->prepare("DELETE FROM " . self::USER_TABLE . " WHERE id = :user_id AND (role = 'veterinario' OR role = 'vet')")
                   ->execute(['user_id' => $user_id]);
            }
            
            $db->commit();
            return ['success' => 'Veterinário excluído com sucesso!'];

        } catch (PDOException $e) {
            if (isset($db) && $db->inTransaction()) { $db->rollBack(); }
            error_log("Erro ao excluir veterinário: " . $e->getMessage());
            return ['error' => 'Erro interno ao excluir veterinário.'];
        }
    }
}