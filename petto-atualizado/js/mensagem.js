document.addEventListener('DOMContentLoaded', function() {
    // Busca o container placeholder ativo (que só tem a classe 'active' se houver $erro ou $sucesso)
    var placeholder = document.querySelector('.alert-placeholder.active');
    
    if (placeholder) {
        // Encontra o alerta ativo (de erro ou sucesso) dentro do placeholder
        var errorAlert = placeholder.querySelector('.alert-error');
        var successAlert = placeholder.querySelector('.alert-success');
        var activeAlert = errorAlert || successAlert;

        if (activeAlert) {
            // 1. Garante que o alerta esteja visível (opacidade 1)
            activeAlert.style.opacity = '1';
            
            // 2. Timeout para iniciar o fade-out após 5 segundos (5000ms)
            setTimeout(function() {
                activeAlert.style.opacity = '0'; // Inicia o fade-out
                
                // 3. Timeout para sumir o elemento (display: none) após a transição de 500ms
                setTimeout(function() {
                    activeAlert.style.display = 'none';
                    // Remove a classe 'active' do placeholder para liberar o espaço
                    var parentPlaceholder = activeAlert.closest('.alert-placeholder');
                    if(parentPlaceholder) parentPlaceholder.classList.remove('active');
                }, 500); // Duração da transição do CSS
            }, 2500); // Tempo total de exibição
        }
    }
});