<?php
// app/views/veterinario/pets.php (Versão Profissional V8)

if (!isset($_SESSION['user']) || !in_array(($_SESSION['user']['role'] ?? ''), ['veterinario', 'vet'])) {
    header("Location: /petto/auth/login");
    exit;
}

// Variáveis injetadas pelo controller
$current_page = $current_page ?? 'pets';
$pets = $pets ?? []; // Lista de pets obtida do Model
$user_name = htmlspecialchars($_SESSION['user']['nome'] ?? 'Profissional');
?>
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
        <li><a href="/petto/veterinario?page=pets" class="<?= $current_page === 'pets' || $current_page === 'prontuario' ? 'active' : '' ?>"><i class="fa fa-paw"></i> Meus Pacientes</a></li>
        <li><a href="/petto/auth/logout"><i class="fa fa-sign-out-alt"></i> Sair</a></li>
    </ul>
</div>

<div class="content">
    <header>
        <h2><i class="fa fa-paw" style="margin-right: 10px; color: var(--highlight-color);"></i> Meus Pacientes</h2>
        <span class="user-info">Dr(a). <?= $user_name ?></span>
    </header>
    
    <div class="main-content">
        <h1 class="section-title" style="border-bottom-color: var(--info-color);">Pacientes Ativos Vinculados</h1>
        <p class="section-subtitle">Utilize a barra de busca para encontrar rapidamente um paciente pelo nome, ID ou Tutor.</p>

        <div class="search-container">
            <i class="fa fa-search search-icon"></i>
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Buscar por Nome, ID do Pet ou Tutor...">
        </div>
        
        <?php if (!empty($pets)): ?>
            <table class="pets-table" id="petsTable">
                <thead>
                    <tr>
                        <th>ID Pet</th>
                        <th>Nome do Pet</th>
                        <th>Espécie / Raça</th>
                        <th>Tutor Principal</th>
                        <th>Tel. Tutor</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pets as $pet): ?>
                    <tr>
                        <td data-search="<?= htmlspecialchars($pet['id_pet']) ?>">#<?= htmlspecialchars($pet['id_pet']) ?></td>
                        <td data-search="<?= htmlspecialchars($pet['nome']) ?>">**<?= htmlspecialchars($pet['nome']) ?>**</td>
                        <td><?= htmlspecialchars($pet['especie']) ?> / <?= htmlspecialchars($pet['raca']) ?></td>
                        <td data-search="<?= htmlspecialchars($pet['nome_tutor']) ?>"><?= htmlspecialchars($pet['nome_tutor']) ?></td>
                        <td><?= htmlspecialchars($pet['telefone_tutor']) ?></td>
                        <td>
                            <a href="/petto/veterinario?page=prontuario&id_pet=<?= htmlspecialchars($pet['id_pet']) ?>" class="btn-prontuario">
                                <i class="fa fa-file-medical"></i> Prontuário
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div style="padding: 20px; background-color: white; border: 1px dashed #ccc; border-radius: 8px; text-align: center;">
                <p style="font-size: 1.1em; color: #555;">
                    <i class="fa fa-info-circle" style="color: var(--warning-color); margin-right: 10px;"></i>
                    Você ainda não tem pacientes associados.
                </p>
            </div>
        <?php endif; ?>

    </div> 
</div>
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
</body>
<script src="/petto/js/pets.js"></script>