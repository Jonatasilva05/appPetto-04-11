<?php
// app/views/veterinario/index.php (Versão Minimalista e Estrita ao Requisito V11)

if (!isset($_SESSION['user']) || !in_array(($_SESSION['user']['role'] ?? ''), ['veterinario', 'vet'])) {
    header("Location: /petto/auth/login");
    exit;
}

$user_name = htmlspecialchars($_SESSION['user']['nome'] ?? 'Profissional');
$current_page = $current_page ?? 'index'; 

// NENHUMA variável de dados simulada ou de fallback é utilizada.

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
        <h2><i class="fa fa-chart-line" style="margin-right: 10px; color: var(--highlight-color);"></i> Dashboard</h2>
        <span class="user-info">Dr(a). <?= $user_name ?></span>
    </header>
    
    <div class="main-content">
        <h1 class="section-title" style="border-bottom-color: var(--primary-color);">Área de Trabalho Principal</h1>
        <p class="section-subtitle">Utilize o menu lateral para gerenciar os pacientes e acessar suas ferramentas.</p>

        <div style="padding: 50px; text-align: center; background-color: white; border: 1px solid var(--border-color); border-radius: 12px; margin-top: 40px;">
            <i class="fa fa-cogs" style="font-size: 3em; color: var(--info-color); margin-bottom: 20px;"></i>
            <p style="font-size: 1.2em; font-weight: 600; color: var(--primary-color);">
                Conteúdo dinâmico em desenvolvimento. Por favor, acesse a seção "Meus Pacientes".
            </p>
        </div>

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