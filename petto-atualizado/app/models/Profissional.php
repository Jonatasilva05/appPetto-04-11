<?php
// app/models/Profissional.php (Versão CORRIGIDA)

require_once __DIR__ . '/../../config/database.php';

class Profissional
{
    private const USER_TABLE = 'usuarios';
    private const VET_TABLE = 'veterinarios';
    private const PET_TABLE = 'pets';
    private const PRONTUARIO_TABLE = 'prontuario';
    private const VACINA_TABLE = 'vacinas';
    private const MEDICAMENTO_TABLE = 'medicamentos';

    private static function getDbConnection(): PDO
    {
        return Database::getConnection();
    }

    // ====================================================
    // MÉTODOS DE LEITURA (GET) 
    // ====================================================

    public static function getVeterinarioDataByUserId(int $userId): ?array { 
        try {
            $db = self::getDbConnection();
            $stmt = $db->prepare("SELECT id_veterinario, user_id, crmv FROM " . self::VET_TABLE . " WHERE user_id = :user_id");
            $stmt->execute(['user_id' => $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao obter dados do veterinário: " . $e->getMessage());
            return null;
        }
    }
    public static function getAllPets(int $veterinarioId): array { 
        try {
            $db = self::getDbConnection();
            $sql = "SELECT p.*, u.nome AS nome_tutor, u.telefone AS telefone_tutor 
                    FROM " . self::PET_TABLE . " p
                    JOIN " . self::USER_TABLE . " u ON p.id_usuario = u.id
                    WHERE p.id_veterinario = :vet_id
                    ORDER BY p.nome ASC";
            
            $stmt = $db->prepare($sql);
            $stmt->execute(['vet_id' => $veterinarioId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erro ao obter lista de pets: " . $e->getMessage());
            return [];
        }
    }
    public static function getPetDetails(int $idPet): ?array { 
        try {
            $db = self::getDbConnection();
            $sql = "SELECT p.*, u.nome AS nome_tutor, u.telefone AS telefone_tutor 
                    FROM " . self::PET_TABLE . " p
                    JOIN " . self::USER_TABLE . " u ON p.id_usuario = u.id
                    WHERE p.id_pet = :id_pet";
            
            $stmt = $db->prepare($sql);
            $stmt->execute(['id_pet' => $idPet]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao obter detalhes do pet: " . $e->getMessage());
            return null;
        }
    }
    public static function getHistorico(int $idPet): array
    {
        $db = self::getDbConnection();
        $historico = [
            'prontuarios' => [],
            'vacinas' => [],
            'medicamentos' => []
        ];

        try {
            // 1. Prontuários (Consultas)
            $stmt_pront = $db->prepare("SELECT * FROM " . self::PRONTUARIO_TABLE . " WHERE id_pet = :id_pet ORDER BY data_consulta DESC");
            $stmt_pront->execute(['id_pet' => $idPet]);
            $historico['prontuarios'] = $stmt_pront->fetchAll(PDO::FETCH_ASSOC);

            // 2. Vacinas
            $stmt_vacina = $db->prepare("SELECT *, nome AS nome_vacina FROM " . self::VACINA_TABLE . " WHERE id_pet = :id_pet ORDER BY data_aplicacao DESC");
            $stmt_vacina->execute(['id_pet' => $idPet]);
            $historico['vacinas'] = $stmt_vacina->fetchAll(PDO::FETCH_ASSOC);

            // 3. Medicamentos
            $stmt_medic = $db->prepare("SELECT * FROM " . self::MEDICAMENTO_TABLE . " WHERE id_pet = :id_pet ORDER BY data_aplicacao DESC");
            $stmt_medic->execute(['id_pet' => $idPet]);
            $historico['medicamentos'] = $stmt_medic->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erro ao obter histórico: " . $e->getMessage());
        }

        return $historico;
    }
    
    // ====================================================
    // MÉTODOS DE ESCRITA (POST) - CORRIGIDOS
    // ====================================================

    /**
     * CORREÇÃO: Inclui 'motivo' e REMOVE 'observacoes' para corresponder ao SQL Dump.
     */
    public static function saveProntuario(array $data, int $veterinarioId): array
    {
        try {
            $db = self::getDbConnection();
            $stmt = $db->prepare("
                INSERT INTO " . self::PRONTUARIO_TABLE . " 
                (id_pet, id_veterinario, data_consulta, motivo, diagnostico, tratamento) 
                VALUES (:id_pet, :id_veterinario, :data_consulta, :motivo, :diagnostico, :tratamento)
            ");

            $stmt->execute([
                'id_pet' => $data['id_pet'],
                'id_veterinario' => $veterinarioId,
                'data_consulta' => $data['data_consulta'],
                'motivo' => $data['motivo'] ?? 'Consulta Clínica', 
                'diagnostico' => $data['diagnostico'] ?? '',
                'tratamento' => $data['tratamento'] ?? ''
            ]);

            return ['success' => 'Consulta registrada com sucesso!'];
        } catch (PDOException $e) {
            error_log("Erro ao salvar prontuário: " . $e->getMessage());
            return ['error' => 'Erro ao registrar a consulta. Tente novamente.'];
        }
    }

    /**
     * CORREÇÃO: Garante que 'proxima_aplicacao' seja enviada, pois é NOT NULL no SQL.
     */
    public static function saveVacina(array $data): array
    {
        try {
            $db = self::getDbConnection();
            $stmt = $db->prepare("
                INSERT INTO " . self::VACINA_TABLE . " 
                (id_pet, nome, data_aplicacao, proxima_aplicacao) 
                VALUES (:id_pet, :nome, :data_aplicacao, :proxima_aplicacao)
            ");
            
            $stmt->execute([
                'id_pet' => $data['id_pet'],
                'nome' => $data['nome_vacina'], 
                'data_aplicacao' => $data['data_aplicacao'],
                'proxima_aplicacao' => $data['proxima_aplicacao'] 
            ]);

            return ['success' => 'Vacina registrada com sucesso!'];
        } catch (PDOException $e) {
            error_log("Erro ao salvar vacina: " . $e->getMessage());
            return ['error' => 'Erro ao registrar a vacina. Tente novamente.'];
        }
    }

    public static function saveMedicamento(array $data): array
    {
        try {
            $db = self::getDbConnection();
            $stmt = $db->prepare("
                INSERT INTO " . self::MEDICAMENTO_TABLE . " 
                (id_pet, id_dataset, nome_medicamento, data_aplicacao) 
                VALUES (:id_pet, :id_dataset, :nome_medicamento, :data_aplicacao)
            ");

            $stmt->execute([
                'id_pet' => $data['id_pet'],
                'id_dataset' => $data['id_dataset'] ?? null,
                'nome_medicamento' => $data['nome_medicamento'],
                'data_aplicacao' => $data['data_aplicacao']
            ]);

            return ['success' => 'Medicamento registrado com sucesso!'];
        } catch (PDOException $e) {
            error_log("Erro ao salvar medicamento: " . $e->getMessage());
            return ['error' => 'Erro ao registrar o medicamento. Tente novamente.'];
        }
    }
}