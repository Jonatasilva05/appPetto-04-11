// /petto/js/register_multistep.js

// =======================================================
// Lógica para Formulário Multi-Passo e Validação
// =======================================================

let currentStep = 1;
const formSteps = document.querySelectorAll('.form-step');

// Função para exibir o passo
function showStep(step) {
    formSteps.forEach(element => {
        element.classList.remove('active');
        if (parseInt(element.dataset.step) === step) {
            element.classList.add('active');
        }
    });
}

// FUNÇÃO DE VALIDAÇÃO: Verifica se todos os 'required' da etapa ATIVA foram preenchidos
function validateCurrentStep() {
    const activeStep = document.querySelector('.form-step.active');
    let isValid = true;
    let firstInvalidInput = null;

    activeStep.querySelectorAll('input[required], select[required], textarea[required]').forEach(input => {
        if (input.type === 'file' && !input.hasAttribute('required')) {
            return;
        }

        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            if (!firstInvalidInput) {
                 firstInvalidInput = input;
            }
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });

    if (!isValid && firstInvalidInput) {
        firstInvalidInput.focus();
    }

    return isValid;
}

// FUNÇÃO PRINCIPAL PARA AVANÇAR: Chama a validação e, se OK, avança
function validateAndNextStep() {
    if (validateCurrentStep()) {
        nextStep();
    }
}

// FUNÇÃO SIMPLES PARA AVANÇAR
function nextStep() {
    if (currentStep < formSteps.length) {
        currentStep++;
        showStep(currentStep);
    }
}

// FUNÇÃO SIMPLES PARA VOLTAR
function prevStep() {
    if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
    }
}

// INICIALIZAÇÃO E VALIDAÇÃO FINAL
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('multi-step-form');
    if (form) {
        form.addEventListener('submit', (event) => {
            // Valida o ÚLTIMO passo antes de submeter
            if (!validateCurrentStep()) {
                event.preventDefault(); // Impede a submissão se houver campos obrigatórios vazios
            }
        });
    }

    // Garante que o primeiro passo seja mostrado ao carregar
    if (formSteps.length > 0) {
        showStep(currentStep);
    }
});