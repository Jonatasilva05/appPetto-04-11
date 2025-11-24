/**
 * utils/validation_utils.js
 * Funções de Validação em JavaScript
 */

/**
 * Função robusta para validar o CPF.
 * @param {string} cpf O número do CPF (pode conter pontuação).
 * @returns {boolean} True se o CPF for válido, False caso contrário.
 */
 function validateCpf(cpf) {
    cpf = cpf.replace(/[^\d]+/g, ''); // Remove caracteres não numéricos

    if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
        return false;
    }

    let soma = 0;
    let resto;

    // Validação do primeiro dígito
    for (let i = 1; i <= 9; i++) {
        soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
    }
    resto = (soma * 10) % 11;
    if ((resto === 10) || (resto === 11)) resto = 0;
    if (resto !== parseInt(cpf.substring(9, 10))) return false;

    soma = 0;
    // Validação do segundo dígito
    for (let i = 1; i <= 10; i++) {
        soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
    }
    resto = (soma * 10) % 11;
    if ((resto === 10) || (resto === 11)) resto = 0;
    if (resto !== parseInt(cpf.substring(10, 11))) return false;

    return true;
}

/**
 * Valida o formato básico de um e-mail.
 * @param {string} email O e-mail a ser validado.
 * @returns {boolean} True se for um formato válido, False caso contrário.
 */
function validateEmail(email) {
    // Regex simples para formato de e-mail (ex: user@domain.com)
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Valida se a senha tem pelo menos 8 caracteres.
 * @param {string} senha A senha a ser validada.
 * @returns {boolean} True se tiver 8 ou mais caracteres, False caso contrário.
 */
function validateSenha(senha) {
    return senha.length >= 8;
}

/**
 * Função para aplicar estilos de validação em tempo real.
 * @param {HTMLElement} inputElement O elemento de input.
 * @param {boolean} isValid Resultado da validação.
 * @param {string} errorMessage Mensagem de erro a ser exibida.
 * @param {string} successMessage Mensagem de sucesso (opcional, para validação on-the-fly).
 */
function applyValidationStyle(inputElement, isValid, errorMessage, successMessage = "Formato válido.") {
    const formGroup = inputElement.closest('.form-group');
    let feedbackElement = formGroup.querySelector('.feedback-message');

    if (!feedbackElement) {
        feedbackElement = document.createElement('span');
        feedbackElement.classList.add('feedback-message');
        formGroup.appendChild(feedbackElement);
    }

    // Remove classes anteriores
    inputElement.classList.remove('is-invalid', 'is-valid');
    feedbackElement.classList.remove('error-message', 'success-message');
    feedbackElement.textContent = '';

    if (inputElement.value.trim() === '') {
        // Se o campo estiver vazio, não aplica estilo de validação (neutro)
        return;
    }
    
    if (isValid) {
        inputElement.classList.add('is-valid');
        feedbackElement.classList.add('success-message');
        feedbackElement.textContent = successMessage;
    } else {
        inputElement.classList.add('is-invalid');
        feedbackElement.classList.add('error-message');
        feedbackElement.textContent = errorMessage;
    }
}

/**
 * Adiciona listeners de evento para validação em tempo real.
 */
document.addEventListener('DOMContentLoaded', () => {
    // Validação de CPF para o registro de veterinário
    const cpfInput = document.querySelector('input[name="cpf"]');
    if (cpfInput) {
        cpfInput.addEventListener('input', (e) => {
            // Aplica máscara simples (opcional, mas ajuda na experiência)
            let value = e.target.value.replace(/\D/g, ""); // Remove tudo que não é dígito
            value = value.replace(/(\d{3})(\d)/, "$1.$2");
            value = value.replace(/(\d{3})(\d)/, "$1.$2");
            value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
            e.target.value = value.substring(0, 14); // Limita ao tamanho máximo com pontuação

            const isValid = validateCpf(e.target.value);
            // MENSAGEM ATUALIZADA AQUI:
            applyValidationStyle(e.target, isValid, "O CPF informado é inválido.");
        });
    }

    // Validação de E-mail (Tutor e Veterinário)
    const emailInputs = document.querySelectorAll('input[name="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('input', (e) => {
            const isValid = validateEmail(e.target.value);
            applyValidationStyle(e.target, isValid, "O formato do e-mail é inválido.");
        });
    });

    // Validação de Senha (Tutor e Veterinário)
    const senhaInputs = document.querySelectorAll('input[name="senha"]');
    senhaInputs.forEach(input => {
        input.addEventListener('input', (e) => {
            const isValid = validateSenha(e.target.value);
            applyValidationStyle(e.target, isValid, "A senha deve ter no mínimo 8 caracteres.");
        });
    });
});