<?php
// app/views/admin/usuarios.php

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /petto/auth");
    exit;
}

// =================================================================
// Lógica para UNIFICAR AS LISTAS em uma única variável para a tabela
// =================================================================
$tutores = $tutores ?? []; 
$veterinarios = $veterinarios ?? []; 

// Mapeia e adiciona a role
$tutores_with_role = array_map(function($u) { $u['role'] = 'tutor'; return $u; }, $tutores);
$veterinarios_with_role = array_map(function($u) { $u['role'] = 'vet'; return $u; }, $veterinarios);

// Combina os dois arrays em uma única lista
$all_users = array_merge($tutores_with_role, $veterinarios_with_role);
?>
<body>

<div class="sidebar">
    <div class="logo">
        <img src="/petto/img/petto-branco.png" alt="Logo Petto Admin">
    </div>
    <ul>
        <li><a href="/petto/admin"><i class="fa fa-chart-pie"></i> Dashboard</a></li>
        <li><a href="/petto/admin?page=usuarios" class="active"><i class="fa fa-users"></i> Usuários</a></li>
        <li><a href="/petto/"><i class="fa fa-sign-out-alt"></i> Sair</a></li>
    </ul>
</div>

<div class="content">
    
    <header>
        <h1>Gestão de Perfis (Tutores e Veterinários)</h1>
        <p>Visão geral e edição completa de todos os perfis de Tutores e Veterinários.</p>
    </header>

    <?php 
    if (isset($_SESSION['flash'])): 
    ?>
        <div class="alert <?= isset($_SESSION['flash']['success']) ? 'alert-success' : 'alert-danger' ?>">
            <?= htmlspecialchars($_SESSION['flash']['success'] ?? $_SESSION['flash']['error']) ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <div class="search-container">
        <input type="text" id="searchInput" onkeyup="filterUsers()" placeholder="Pesquisar por Nome, E-mail, ID, ou Cargo (Tutor/Veterinário)..." title="Digite o termo de busca">
    </div>

    <div class="list-section">
        <h3>Listagem de Usuários (Total: <?= count($all_users) ?>)</h3>
        <div class="table-responsive"> 
            <table class="data-table filterable-table" id="usersTable"> 
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Tipo</th> 
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($all_users as $user): 
                        // Note: O JSON está codificado em PHP, mas será usado pelo JS externo
                        $userJson = htmlspecialchars(json_encode($user), ENT_QUOTES, 'UTF-8');
                        $avatar_url = htmlspecialchars($user['foto_url'] ?? '');
                        $initials = strtoupper(substr($user['nome'] ?? 'NA', 0, 1));
                        $role_text = ($user['role'] === 'tutor') ? 'Tutor' : 'Veterinário';
                    ?>
                        <tr class="data-row" data-search="<?= htmlspecialchars($user['id'] . ' ' . $user['nome'] . ' ' . $user['email'] . ' ' . $role_text) ?>"> 
                            <td><?= htmlspecialchars($user['id'] ?? 'N/A') ?></td>
                            <td>
                                <div class="user-name-cell">
                                    <?php if ($avatar_url): ?>
                                        <img src="<?= $avatar_url ?>" alt="Foto" class="user-avatar" loading="lazy">
                                    <?php else: ?>
                                        <div class="user-avatar-placeholder"><?= $initials ?></div>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($user['nome'] ?? 'N/A') ?>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($user['email'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($user['telefone'] ?? 'N/A') ?></td>
                            <td>
                                <span class="role-tag <?= htmlspecialchars($user['role']) ?>">
                                    <?= $role_text ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-edit" onclick="openEditModal('<?= htmlspecialchars($user['role']) ?>', <?= $userJson ?>)">
                                    <i class="fa fa-edit"></i> Editar
                                </button>
                                
                                <form action="/petto/admin?page=usuarios" method="POST" style="display:inline-block;" onsubmit="return confirm('ATENÇÃO: Deseja realmente excluir o usuário <?= htmlspecialchars($user['nome']) ?> (<?= $role_text ?>)? Esta ação é irreversível.');">
                                    <input type="hidden" name="acao" value="excluir">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                                    <input type="hidden" name="role" value="<?= htmlspecialchars($user['role']) ?>">
                                    <button type="submit" class="btn btn-sm btn-delete">
                                        <i class="fa fa-trash"></i> Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h3 id="modalTitle">Editar Usuário</h3>
        
        <form id="editForm" action="/petto/admin?page=usuarios" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="acao" value="editar">
            <input type="hidden" name="id" id="idInput" value="">
            <input type="hidden" name="role" id="roleInput" value="">
            <input type="hidden" name="foto_url_current" id="foto_url_current"> 
            <input type="hidden" name="user_id" id="userIdInput" value=""> 
            
            <div class="photo-update-container">
                <img id="modalPhotoPreview" src="" alt="Pré-visualização da Foto">

                <div class="photo-input-group">
                    <div class="form-group">
                        <label for="foto_file">Atualizar Foto (Escolher Arquivo):</label>
                        <input type="file" id="foto_file" name="foto_file" accept="image/*">
                        <small style="color: #6c757d;">O preview muda imediatamente ao selecionar o arquivo.</small>
                    </div>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone:</label>
                    <input type="text" id="telefone" name="telefone">
                </div>
                <div class="form-group">
                    <label for="endereco">Endereço:</label>
                    <input type="text" id="endereco" name="endereco">
                </div>
                <div class="form-group">
                    <label for="pet_primario">Pet Primário:</label>
                    <input type="text" id="pet_primario" name="pet_primario">
                </div>
                <div class="form-group">
                    <label for="cor_favorita">Cor Favorita:</label>
                    <input type="text" id="cor_favorita" name="cor_favorita">
                </div>
                <div class="form-group">
                    <label for="senha">Nova Senha (opcional):</label>
                    <input type="password" id="senha" name="senha" placeholder="Deixe em branco para não alterar">
                </div>
                <div></div>

                <div id="vetFields" class="form-fieldset">
                    <h4>Dados Profissionais do Veterinário</h4>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="cpf">CPF:</label>
                            <input type="text" id="cpf" name="cpf">
                        </div>
                        <div class="form-group">
                            <label for="crmv">CRMV:</label>
                            <input type="text" id="crmv" name="crmv">
                        </div>
                        <div class="form-group">
                            <label for="nome_clinica">Clínica:</label>
                            <input type="text" id="nome_clinica" name="nome_clinica">
                        </div>
                        <div class="form-group">
                            <label for="tempo_experiencia">Tempo Exp.:</label>
                            <input type="text" id="tempo_experiencia" name="tempo_experiencia">
                        </div>
                        <div class="form-group">
                            <label for="cep_clinica">CEP Clínica:</label>
                            <input type="text" id="cep_clinica" name="cep_clinica">
                        </div>
                        <div class="form-group">
                            <label for="bairro_clinica">Bairro Clínica:</label>
                            <input type="text" id="bairro_clinica" name="bairro_clinica">
                        </div>
                        <div class="form-group">
                            <label for="numero_clinica">Número Clínica:</label>
                            <input type="text" id="numero_clinica" name="numero_clinica">
                        </div>
                    </div>
                </div>

            </div>
            
            <button type="submit" class="btn btn-primary full-width mt-3">Salvar Alterações</button>
        </form>
    </div>
</div>
</body>

<link rel="stylesheet" href="/petto/css/admin-usuarios.css">  
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
<script src="/petto/js/usuarios.js"></script>