<?php
// app/views/auth/register_tutor.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$erro = $_SESSION['erro'] ?? '';
$sucesso = $_SESSION['sucesso'] ?? '';
unset($_SESSION['erro'], $_SESSION['sucesso']);

// Lógica de Retenção de Dados
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
                <h2 data-lang-key="register-tutor-h2"></h2>
                <p data-lang-key="register-tutor-p"></p>
            </div>
        </div>

        <div class="register-container">
            
            <a href="/petto/auth/login" class="btn-back-link" title="Voltar para o Login">
                <i class="fas fa-arrow-left"></i>
            </a>

            <div class="form-wrapper active" id="tutor-registration">
                <h2 class="form-title"><span data-lang-key="register-title-tutor"></span></h2> 

                <div class="register-type-selector">
                    <a href="/petto/auth/register_tutor" class="active" data-lang-key="register-link-tutor"></a>
                    <a href="/petto/auth/register_vet" data-lang-key="register-link-vet"></a>
                </div>
                
                <div class="alert-placeholder <?= ($erro || $sucesso) ? 'active' : '' ?>">
                    <?php if ($erro): ?>
                        <div class="alert alert-error"><?= $erro; ?></div>
                    <?php endif; ?>
                    <?php if ($sucesso): ?>
                        <div class="alert alert-success"><?= $sucesso; ?></div>
                    <?php endif; ?>
                </div>

                <form id="multi-step-form" action="/petto/auth/register_tutor" method="post" enctype="multipart/form-data" onsubmit="return validateCurrentStep(event)">
                    
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
                            <input type="password" name="senha" required>
                        </div>
                        <div class="form-group">
                            <label data-lang-key="register-phone-label"></label>
                            <input type="text" name="telefone" value="<?= htmlspecialchars($post_data['telefone'] ?? '') ?>">
                        </div>
                        <div class="step-buttons">
                             <button type="button" class="btn-base btn-secondary-base btn-step" style="flex: 1; visibility: hidden;">Voltar</button>
                             <button type="button" class="btn-base btn-highlight btn-step" onclick="validateAndNextStep()" data-lang-key="register-btn-continue"></button>
                        </div>
                    </div>
                    
                    <div class="form-step" data-step="2">
                        <div class="form-group">
                            <label data-lang-key="register-address-label"></label>
                            <input type="text" name="endereco" required value="<?= htmlspecialchars($post_data['endereco'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label data-lang-key="register-profile-photo-label"></label>
                            <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*">
                        </div>
                        
                        <div class="form-group">
                            <label data-lang-key="register-first-pet-label"></label>
                            <input type="text" name="pet_primario" required value="<?= htmlspecialchars($post_data['pet_primario'] ?? '') ?>">
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
                    <p>
                        <span data-lang-key="register-already-have-account"></span>
                        <a href="/petto/auth/login" data-lang-key="register-login-link"></a>
                    </p>
                </div>
            </div>
        </div>
    </section>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="/petto/js/traduzir.js"></script>
<script src="/petto/js/register_multistep.js"></script>