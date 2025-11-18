<?php 
// app/views/veterinario/prontuario.php (Versão Profissional V15 - Design com Modais)

if (!isset($_SESSION['user']) || !in_array(($_SESSION['user']['role'] ?? ''), ['veterinario', 'vet'])) {
    header("Location: /petto/auth/login");
    exit;
}

// Variáveis injetadas pelo controller
$pet_details = $pet_details ?? null; 
$historico = $historico ?? ['prontuarios' => [], 'vacinas' => [], 'medicamentos' => []];
$id_pet = $pet_details['id_pet'] ?? 0;
$current_page = 'pets'; 

// Prepara o histórico unificado e ordenado para a timeline (Estrito aos campos do DB)
$timeline_items = [];

// 1. Prontuários (Consulta) - Campos de DB: data_consulta, motivo, diagnostico, tratamento
foreach ($historico['prontuarios'] as $item) {
    $timeline_items[] = [
        'type' => 'consulta', 
        'date' => $item['data_consulta'] ?? '', 
        'motivo' => $item['motivo'] ?? '',
        'diagnostico' => $item['diagnostico'] ?? '',
        'tratamento' => $item['tratamento'] ?? '',
        'id' => $item['id'] ?? null
    ];
}

// 2. Vacinas - Campos de DB: nome, data_aplicacao, proxima_aplicacao, id_dataset
foreach ($historico['vacinas'] as $item) {
    $timeline_items[] = [
        'type' => 'vacina', 
        'date' => $item['data_aplicacao'] ?? '', 
        'nome' => $item['nome'] ?? '',
        'proxima_aplicacao' => $item['proxima_aplicacao'] ?? '',
        'id_dataset' => $item['id_dataset'] ?? null,
    ];
}

// 3. Medicamentos - Campos de DB: nome_medicamento, data_aplicacao, id_dataset
foreach ($historico['medicamentos'] as $item) {
    $timeline_items[] = [
        'type' => 'medicamento', 
        'date' => $item['data_aplicacao'] ?? '', 
        'nome_medicamento' => $item['nome_medicamento'] ?? '',
        'id_dataset' => $item['id_dataset'] ?? null,
    ];
}

// Ordena a timeline por data decrescente (mais recente primeiro)
usort($timeline_items, function($a, $b) {
    $date_a = $a['date'] ?? '0000-00-00';
    $date_b = $b['date'] ?? '0000-00-00';
    return strtotime($date_b) - strtotime($date_a);
});

?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/petto/css/veterinario.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
<body>

<div class="sidebar">
    <div class="logo">
        <img src="/petto/img/petto-branco.png" alt="Petto Vet Logo">
    </div>
    <ul>
        <li><a href="/petto/veterinario?page=index" class="<?= $current_page === 'index' ? 'active' : '' ?>"><i class="fa fa-chart-pie"></i> Dashboard</a></li>
        <li><a href="/petto/veterinario?page=pets" class="<?= $current_page === 'pets' || ($current_page === 'prontuario' && isset($_GET['id_pet'])) ? 'active' : '' ?>"><i class="fa fa-paw"></i> Meus Pacientes</a></li>
        <li><a href="/petto/auth/logout"><i class="fa fa-sign-out-alt"></i> Sair</a></li>
    </ul>
</div>

<div class="content">
    <header>
        <h2><i class="fa fa-file-medical" style="margin-right: 10px; color: var(--highlight-color);"></i> Prontuário Veterinário</h2>
        <span class="user-info">Dr(a). **<?= htmlspecialchars($_SESSION['user']['nome'] ?? 'Profissional') ?>**</span>
    </header>
    
    <?php if (isset($_SESSION['flash']['success'])): ?>
        <div class="flash-message flash-success">
            <i class="fa fa-check-circle"></i> <?= $_SESSION['flash']['success']; unset($_SESSION['flash']['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['flash']['error'])): ?>
        <div class="flash-message flash-error">
            <i class="fa fa-xmark-circle"></i> <?= $_SESSION['flash']['error']; unset($_SESSION['flash']['error']); ?>
        </div>
    <?php endif; ?>

    <div class="main-content">
        <?php if ($pet_details): ?>
            
            <a href="/petto/veterinario?page=pets" class="btn-back-list"><i class="fa fa-arrow-left"></i> Voltar para a Lista de Pacientes</a>

            <h1 class="section-title" style="border-bottom-color: var(--info-color); margin-bottom: 30px;">
                Ficha Clínica: **<?= htmlspecialchars($pet_details['nome']) ?>**
            </h1>
            
            <div class="prontuario-info-header">
                <h3>
                    <i class="fa fa-id-card-clip" style="margin-right: 5px; color: var(--info-color);"></i> Dados de Identificação do Paciente
                </h3>
                <div class="prontuario-dados-grid">
                    <div>
                        <strong>Pet ID:</strong> #<?= htmlspecialchars($id_pet) ?><br>
                        <strong>Espécie/Raça:</strong> <?= htmlspecialchars($pet_details['especie']) ?> / <?= htmlspecialchars($pet_details['raca']) ?><br>
                        <strong>Nascimento:</strong> <?php try { echo date('d/m/Y', strtotime($pet_details['data_nascimento'])); } catch (Exception $e) { echo 'N/A'; } ?>
                    </div>
                    <div>
                        <strong>Tutor:</strong> <?= htmlspecialchars($pet_details['nome_tutor']) ?><br>
                        <strong>Telefone:</strong> <?= htmlspecialchars($pet_details['telefone_tutor']) ?><br>
                        <strong>E-mail:</strong> <?= htmlspecialchars($pet_details['email_tutor'] ?? 'N/A') ?>
                    </div>
                </div>
            </div>

            <h2 class="section-title" style="border-bottom-color: var(--primary-color);"><i class="fa fa-history"></i> Histórico Clínico (Timeline)</h2>

            <div class="clinical-action-bar">
                <button class="btn-open-modal consulta" data-modal-target="modal-consulta">
                    <i class="fa fa-file-medical"></i> Nova Consulta
                </button>
                <button class="btn-open-modal vacina" data-modal-target="modal-vacina">
                    <i class="fa fa-syringe"></i> Adicionar Vacina
                </button>
                <button class="btn-open-modal medicamento" data-modal-target="modal-medicamento">
                    <i class="fa fa-pills"></i> Adicionar Prescrição
                </button>
            </div>
            
            
            <div class="historico-timeline">
                <?php if (!empty($timeline_items)): ?>
                    <?php foreach ($timeline_items as $item): ?>
                        <div class="historico-item <?= $item['type'] ?>">
                            <?php 
                                $date_obj = DateTime::createFromFormat('Y-m-d H:i:s', $item['date']) ?: DateTime::createFromFormat('Y-m-d', $item['date']);
                                $date_formatted = $date_obj ? $date_obj->format('d/m/Y') : 'N/A';
                            ?>
                            <span class="historico-date"><i class="fa fa-calendar-alt" style="margin-right: 5px;"></i> <?= $date_formatted ?></span>

                            <?php if ($item['type'] === 'consulta'): ?>
                                <div class="historico-details">
                                    <div class="header" style="color: var(--info-color);">
                                        <i class="fa fa-stethoscope" style="margin-right: 10px;"></i> 
                                        ATENDIMENTO CLÍNICO
                                    </div>
                                    
                                    <strong style="color: var(--info-color);">Motivo (DB: motivo):</strong> <?= nl2br(htmlspecialchars($item['motivo'])) ?><br>
                                    <strong style="color: var(--danger-color);">Diagnóstico (DB: diagnostico):</strong> <?= nl2br(htmlspecialchars($item['diagnostico'])) ?><br>
                                    <strong style="color: var(--highlight-color);">Tratamento (DB: tratamento):</strong> <?= nl2br(htmlspecialchars($item['tratamento'] ?: 'Nenhum plano registrado.')) ?>
                                </div>
                            <?php elseif ($item['type'] === 'vacina'): ?>
                                <div class="historico-details">
                                    <div class="header" style="color: var(--success-color);">
                                        <i class="fa fa-syringe" style="margin-right: 10px;"></i> 
                                        VACINA: <?= htmlspecialchars($item['nome']) ?> (DB: nome)
                                    </div>
                                    <strong style="color: var(--primary-color);">Data Aplicação (DB: data_aplicacao):</strong> <?= $date_formatted ?><br>
                                    <?php 
                                        $proxima_date_obj = DateTime::createFromFormat('Y-m-d H:i:s', $item['proxima_aplicacao']) ?: DateTime::createFromFormat('Y-m-d', $item['proxima_aplicacao']);
                                        $proxima_date_formatted = $proxima_date_obj ? $proxima_date_obj->format('d/m/Y') : 'N/A';
                                    ?>
                                    <strong style="color: var(--primary-color);">Próxima Dose (DB: proxima_aplicacao):</strong> <?= $proxima_date_formatted ?>
                                </div>
                            <?php elseif ($item['type'] === 'medicamento'): ?>
                                <div class="historico-details">
                                    <div class="header" style="color: var(--warning-color);">
                                        <i class="fa fa-pills" style="margin-right: 10px;"></i> 
                                        MEDICAMENTO: <?= htmlspecialchars($item['nome_medicamento']) ?> (DB: nome_medicamento)
                                    </div>
                                    <strong style="color: var(--primary-color);">Data Início (DB: data_aplicacao):</strong> <?= $date_formatted ?><br>
                                    <?php if ($item['id_dataset']): ?>
                                        <small style="color: #666;">ID Dataset (DB: id_dataset): <?= htmlspecialchars($item['id_dataset']) ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="padding: 20px; background-color: white; border: 2px dashed #ccc; border-radius: 10px; text-align: center; color: #777; font-weight: 600;">
                        <i class="fa fa-file-text-o" style="margin-right: 5px; color: var(--info-color);"></i>
                        Nenhum registro clínico encontrado para este paciente. Use os botões acima para iniciar um novo lançamento.
                    </div>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <h1 class="section-title">Pet Não Encontrado</h1>
            <p class="section-subtitle">O ID do pet é inválido ou houve um erro ao carregar os dados. Por favor, volte à lista de pacientes.</p>
        <?php endif; ?>

    </div>
</div>

<div id="modal-consulta" class="modal">
    <div class="modal-content consulta">
        <span class="close-button" data-modal-target="modal-consulta">&times;</span>
        <h3><i class="fa fa-clipboard-check"></i> Registro de Nova Consulta</h3>
        
        <form method="POST" action="/petto/veterinario?page=prontuario&id_pet=<?= $id_pet ?>">
            <input type="hidden" name="action" value="novo_prontuario">
            
            <div class="form-row-grid">
                <div>
                    <label>Data da Consulta (DB: data_consulta):</label>
                    <input type="date" name="data_consulta" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div>
                    <label>Motivo da Visita (DB: motivo):</label>
                    <input type="text" name="motivo" placeholder="Queixa principal do tutor" required>
                </div>
            </div>

            <label style="margin-top: 15px;"><i class="fa fa-microscope" style="color: var(--info-color);"></i> **Diagnóstico (DB: diagnostico) (Obrigatório):**</label>
            <textarea name="diagnostico" rows="4" required placeholder="Conclusão clínica."></textarea>

            <label style="margin-top: 15px;"><i class="fa fa-hand-holding-medical" style="color: var(--highlight-color);"></i> **Tratamento/Plano (DB: tratamento):**</label>
            <textarea name="tratamento" rows="4" placeholder="Plano terapêutico, prescrição ou exames."></textarea>
            
            <div class="modal-footer">
                <button type="submit" class="btn-save-modal">
                    <i class="fa fa-floppy-disk" style="margin-right: 10px;"></i> Salvar Consulta
                </button>
            </div>
        </form>
    </div>
</div>

<div id="modal-vacina" class="modal">
    <div class="modal-content vacina">
        <span class="close-button" data-modal-target="modal-vacina">&times;</span>
        <h3><i class="fa fa-syringe"></i> Adicionar Nova Vacina</h3>

        <form method="POST" action="/petto/veterinario?page=prontuario&id_pet=<?= $id_pet ?>">
            <input type="hidden" name="action" value="add_vacina">

            <label>Nome da Vacina (DB: nome):</label>
            <input type="text" name="nome_vacina" required placeholder="Ex: V10, Antirrábica">

            <div class="form-row-grid">
                <div>
                    <label>Data Aplicação (DB: data_aplicacao):</label>
                    <input type="date" name="data_aplicacao" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div>
                    <label>Próxima Aplicação (DB: proxima_aplicacao):</label>
                    <input type="date" name="proxima_aplicacao" value="<?= date('Y-m-d', strtotime('+1 year')) ?>" required>
                </div>
            </div>
            
            <label>ID Dataset (DB: id_dataset) (Opcional):</label>
            <input type="text" name="id_dataset" placeholder="Ex: Lote 1234">
            
            <div class="modal-footer">
                <button type="submit" class="btn-save-modal">Salvar Vacina</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-medicamento" class="modal">
    <div class="modal-content medicamento">
        <span class="close-button" data-modal-target="modal-medicamento">&times;</span>
        <h3><i class="fa fa-pills"></i> Adicionar Prescrição / Medicamento</h3>

        <form method="POST" action="/petto/veterinario?page=prontuario&id_pet=<?= $id_pet ?>">
            <input type="hidden" name="action" value="add_medicamento">

            <label>Nome do Medicamento (DB: nome_medicamento):</label>
            <input type="text" name="nome_medicamento" required placeholder="Ex: Meloxicam 0,5mg (1x/dia)">
            
            <div class="form-row-grid">
                <div>
                    <label>Data Início/Aplicação (DB: data_aplicacao):</label>
                    <input type="date" name="data_aplicacao" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div>
                    <label>ID Dataset (DB: id_dataset) (Opcional):</label>
                    <input type="text" name="id_dataset" placeholder="Ex: Lote 1234">
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-save-modal">Salvar Prescrição</button>
            </div>
        </form>
    </div>
</div>
</body>
<div vw class="enabled">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper>
        <div class="vw-plugin-top-wrapper"></div>
    </div>
</div>
<script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
<script>
    new window.VLibras.Widget('https://vlibras.gov.br/app');
</script>
<script src="/petto/js/veterinario.js"></script>