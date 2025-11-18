<?php
// app/views/auth/register_vet.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Buscando e limpando mensagens de erro/sucesso da sessão
$erro = $_SESSION['erro'] ?? '';
$sucesso = $_SESSION['sucesso'] ?? '';
unset($_SESSION['erro'], $_SESSION['sucesso']);

// Lógica de Retenção de Dados: Puxa dados salvos na sessão em caso de erro
$post_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']); 
?>
<body class="register-body">

    <section class="register-section">
        
        <div class="register-overlay">
            <a href="/petto/auth/login" class="btn-back-mobile" title="Voltar para o Login">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="overlay-content">
                <h2 data-lang-key="register-vet-h2"></h2>
                <p data-lang-key="register-vet-p"></p>
            </div>
        </div>

        <div class="register-container">
            
            <a href="/petto/auth/login" class="btn-back-link" title="Voltar para o Login">
                <i class="fas fa-arrow-left"></i>
            </a>

            <div class="form-wrapper active" id="vet-registration">
                
                <h2 class="form-title"><span data-lang-key="register-title-vet"></span></h2> 

                <div class="register-type-selector">
                    <a href="/petto/auth/register_tutor" data-lang-key="register-link-tutor"></a>
                    <a href="/petto/auth/register_vet" class="active" data-lang-key="register-link-vet"></a>
                </div>
                
                <div class="alert-placeholder <?= ($erro || $sucesso) ? 'active' : '' ?>">
                    <?php if ($erro): ?>
                        <div class="alert alert-error"><?= $erro; ?></div>
                    <?php endif; ?>
                    <?php if ($sucesso): ?>
                        <div class="alert alert-success"><?= $sucesso; ?></div>
                    <?php endif; ?>
                </div>

                <form id="multi-step-form" action="/petto/auth/register_vet" method="POST" enctype="multipart/form-data" onsubmit="return validateCurrentStep(event)">
                    
                    <div class="form-step active" data-step="1">
                        <div class="form-group">
                            <label data-lang-key="register-name-label"></label>
                            <input type="text" name="nome" required value="<?= htmlspecialchars($post_data['nome'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label data-lang-key="register-email-label"></label>
                            <input type="email" name="email" required value="<?= htmlspecialchars($post_data['email'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label data-lang-key="register-password-label"></label>
                            <input type="password" name="senha" id="vet-senha" required>
                        </div>
                        <div class="form-group">
                            <label data-lang-key="register-phone-label"></label>
                            <input type="text" name="telefone" value="<?= htmlspecialchars($post_data['telefone'] ?? '') ?>">
                        </div>
                        <div class="step-buttons">
                            <button type="button" class="btn-base btn-secondary-base btn-step" style="flex: 1; visibility: hidden;" data-lang-key="register-btn-back"></button>
                            <button type="button" class="btn-base btn-highlight btn-step" onclick="validateAndNextStep()" data-lang-key="register-btn-continue"></button>
                        </div>
                    </div>
                    
                    <div class="form-step" data-step="2">
                        <div class="form-group">
                            <label data-lang-key="register-profile-photo-label"></label>
                            <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label data-lang-key="register-address-label"></label>
                            <input type="text" name="endereco_pessoal" value="<?= htmlspecialchars($post_data['endereco_pessoal'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>CPF:</label>
                            <input type="text" name="cpf" value="<?= htmlspecialchars($post_data['cpf'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label data-lang-key="register-crvm-label"></label>
                            <input type="text" name="crmv" required value="<?= htmlspecialchars($post_data['crmv'] ?? '') ?>">
                        </div>

                        <div class="step-buttons">
                            <button type="button" class="btn-base btn-secondary-base btn-step" onclick="prevStep()" data-lang-key="register-btn-back"></button>
                            <button type="button" class="btn-base btn-highlight btn-step" onclick="validateAndNextStep()" data-lang-key="register-btn-continue"></button>
                        </div>
                    </div>

                    <div class="form-step" data-step="3">
                        <div class="form-group">
                            <label>Nome da Clínica/Consultório:</label>
                            <input type="text" name="nome_clinica" required value="<?= htmlspecialchars($post_data['nome_clinica'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Tempo de Experiência (anos):</label>
                            <input type="number" name="tempo_experiencia" required value="<?= htmlspecialchars($post_data['tempo_experiencia'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Endereço da Clínica (Rua, Av.):</label>
                            <input type="text" name="endereco_clinica" value="<?= htmlspecialchars($post_data['endereco_clinica'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Número da Clínica:</label>
                            <input type="text" name="numero_clinica" value="<?= htmlspecialchars($post_data['numero_clinica'] ?? '') ?>">
                        </div>

                        
                        <div class="step-buttons">
                            <button type="button" class="btn-base btn-secondary-base btn-step" onclick="prevStep()" data-lang-key="register-btn-back"></button>
                            <button type="button" class="btn-base btn-highlight btn-step" onclick="validateAndNextStep()" data-lang-key="register-btn-continue"></button>
                        </div>
                    </div>

                    <div class="form-step" data-step="4">
                        <div class="form-group">
                            <label>Bairro da Clínica:</label>
                            <input type="text" name="bairro_clinica" required value="<?= htmlspecialchars($post_data['bairro_clinica'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>CEP da Clínica:</label>
                            <input type="text" name="cep_clinica" value="<?= htmlspecialchars($post_data['cep_clinica'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label data-lang-key="register-first-pet-label"></label>
                            <input type="text" name="pet_primario" value="<?= htmlspecialchars($post_data['pet_primario'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label data-lang-key="register-favorite-color-label"></label>
                            <input type="text" name="cor_favorita" value="<?= htmlspecialchars($post_data['cor_favorita'] ?? '') ?>">
                        </div>
                        <div class="step-buttons">
                            <button type="button" class="btn-base btn-secondary-base btn-step" onclick="prevStep()" data-lang-key="register-btn-back"></button>
                            <button type="submit" class="btn-base btn-highlight btn-step" data-lang-key="register-btn-register"></button>
                        </div>
                    </div>

                </form>

                <div class="registration-footer">
                    <p><span data-lang-key="register-already-have-account"></span> <a href="/petto/auth/login" data-lang-key="register-login-link"></a></p>
                </div>
            </div>
        </div>
    </section>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="/petto/js/traduzir.js"></script>
    <script src="/petto/js/mensagem.js"></script>
    <script src="/petto/js/register_multistep.js"></script>