<?php
// app/views/admin/dashboard.php

// Verifica se o usuário está logado e se é admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /petto/auth");
    exit;
}
?>
    <link rel="stylesheet" href="/petto/css/admin.css"> 
    <link rel="stylesheet" href="/petto/css/admin-usuarios.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
<body>

<div class="sidebar">
    <div class="logo">
        <img src="/petto/img/petto-branco.png" alt="Logo Petto Admin">
    </div>
    <ul>
        <li><a href="/petto/admin" class="active"><i class="fa fa-chart-pie"></i> Dashboard</a></li>
        <li><a href="/petto/admin?page=usuarios"><i class="fa fa-users"></i> Usuários</a></li>
        <li><a href="/petto/"><i class="fa fa-sign-out-alt"></i> Sair</a></li>
    </ul>
</div>

<div class="content">
    
    <header>
        <h1>Bem-vindo(a), <?= htmlspecialchars($_SESSION['user']['nome'] ?? 'Administrador(a)') ?>!</h1>
        <p>Visão geral e resumo das principais atividades do sistema Petto.</p>
    </header>

    <div class="widget-grid">
        <div class="widget primary">
            <div class="icon-wrap"><i class="fa fa-users"></i></div> 
            <div class="info">
                <h2 class="label-heading">Usuários</h2> 
            </div>
        </div>
        <div class="widget success">
            <div class="icon-wrap"><i class="fa fa-user"></i></div> 
            <div class="info">
                <h2 class="label-heading">Tutores</h2>
            </div>
        </div>
        <div class="widget info">
            <div class="icon-wrap"><i class="fa fa-user-md"></i></div>
            <div class="info">
                <h2 class="label-heading">Veterinários</h2>
            </div>
        </div>
        <div class="widget warning">
            <div class="icon-wrap"><i class="fa fa-paw"></i></div>
            <div class="info">
                <h2 class="label-heading">Pets</h2>
            </div>
        </div>
    </div>
    
    <div class="secondary-grid">
        
        <div class="status-section">
            <h3>Status Operacional dos Serviços</h3>
            
            <ul class="status-list">
                <li class="status-online"><i class="fa fa-check-circle"></i> Serviço de Login/Autenticação <span>ONLINE</span></li>
                <li class="status-online"><i class="fa fa-check-circle"></i> Conexão com Banco de Dados <span>ONLINE</span></li>
                <li class="status-online"><i class="fa fa-check-circle"></i> Serviço de Upload de Imagens <span>ONLINE</span></li> 
            </ul>
        </div>
        
        <div class="quick-access-section">
            <h3>🔗 Acesso Rápido</h3>
            <ul class="quick-links">
                <li><a href="/petto/admin?page=usuarios&action=novo"><i class="fa fa-user-plus"></i> Novo Cadastro</a></li>
                <li><a href="/petto/admin?page=usuarios"><i class="fa fa-user-md"></i> Ver Veterinários</a></li>
            </ul>
        </div>
    </div>
    <div class="main-section">
        <h3>Atividades Recentes do Sistema</h3>
        <p class="intro-text">
            Esta seção mostrará o log de atividades (novos cadastros, consultas agendadas, exclusões, etc.).
            Implemente a lógica de consulta ao banco de dados aqui.
        </p>
        
        <ul class="activity-list">
            <li><i class="fa fa-circle-info info-icon"></i> <span class="text">Sistema inicializado com sucesso.</span> <span class="date">2025</span></li>
            <li><i class="fa fa-circle-info info-icon"></i> <span class="text">Aguardando novos eventos e dados.</span> <span class="date">2025</span></li>
        </ul>
        
    </div>
    
</div>
</body>
