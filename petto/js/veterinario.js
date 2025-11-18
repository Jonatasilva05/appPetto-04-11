    document.addEventListener('DOMContentLoaded', function() {
        const modalButtons = document.querySelectorAll('.btn-open-modal');
        const closeButtons = document.querySelectorAll('.close-button');
        const modals = document.querySelectorAll('.modal');

        // Função para abrir o modal
        modalButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-modal-target');
                document.getElementById(targetId).style.display = 'block';
            });
        });

        // Função para fechar o modal
        function closeModal(modalElement) {
            modalElement.style.display = 'none';
        }

        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-modal-target');
                closeModal(document.getElementById(targetId));
            });
        });

        // Fechar o modal clicando fora dele
        modals.forEach(modal => {
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeModal(modal);
                }
            });
        });
    });