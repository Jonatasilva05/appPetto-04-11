<?php
// app/views/auth/login.php

// Certifique-se de iniciar a sessão antes de usar $_SESSION.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Variáveis de erro/sucesso (se existirem)
?>
<body class="register-body">

    <section class="register-section">
        
        <div class="register-overlay">
            <a href="/petto/index" class="btn-back-mobile" title="Voltar para a página inicial">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="overlay-content">
                <h2 data-lang-key="login-overlay-h2"></h2>
                <p data-lang-key="login-overlay-p"></p>
                
            </div>
        </div>

        <div class="register-container">
            
            <a href="/petto/index" class="btn-back-link" title="Voltar para a página inicial">
                <i class="fas fa-arrow-left"></i>
            </a>

            <div class="form-wrapper active" id="login-form">
                <h2 class="form-title"><span data-lang-key="login-title"></span></h2> 

                <div class="alert-placeholder <?= (isset($_SESSION['erro']) || isset($_SESSION['sucesso'])) ? 'active' : '' ?>">
                    <?php if (isset($_SESSION['erro'])): ?>
                        <div class="alert alert-error" id="login-error-alert"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['sucesso'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
                    <?php endif; ?>
                </div>

                <form method="POST" action="/petto/auth/login">
                    
                    <div class="form-group">
                        <label for="email" data-lang-key="login-email-label"></label>
                        <input type="email" name="email" id="email" required>
                    </div>

                    <div class="form-group">
                        <label for="senha" data-lang-key="login-password-label"></label>
                        <input type="password" name="senha" id="senha" required>
                    </div>

                    <button type="submit" class="btn-highlight full-width btn-base" data-lang-key="login-btn-enter"></button>
                    
                </form>

                <div class="forgot-password-link">
                    <a href="#" data-lang-key="login-forgot-password"></a>
                </div>

                <div class="registration-footer">
                    <p><span data-lang-key="login-no-account"></span> 
                        <a href="/petto/auth/register_tutor" data-lang-key="login-register-link"></a>
                    </p>
                </div>
            </div>
        </div>
    </section>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="/petto/js/traduzir.js"></script>