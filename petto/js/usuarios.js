// =====================================================
// JavaScript APRIMORADO (USUÁRIOS)
// =====================================================

const PLACEHOLDER_SVG = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23ccc'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E";

// --- FUNÇÕES DE FILTRO E PESQUISA ---
function filterUsers() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toUpperCase();
    const table = document.getElementById('usersTable');
    const tr = table.getElementsByClassName('data-row');
    
    for (let i = 0; i < tr.length; i++) {
        const rowData = tr[i].getAttribute('data-search');
        
        if (rowData && rowData.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = ''; 
        } else {
            tr[i].style.display = 'none'; 
        }
    }
}

// --- FUNÇÕES DE PRÉ-VISUALIZAÇÃO DE IMAGEM ---
function updatePhotoPreviewFromFile(input) {
    const preview = document.getElementById('modalPhotoPreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.onerror = null; 
        }
        reader.readAsDataURL(input.files[0]); 
    } else {
        const currentUrl = document.getElementById('foto_url_current').value;
        updatePhotoPreview(currentUrl);
    }
}

function updatePhotoPreview(url) {
    const preview = document.getElementById('modalPhotoPreview');
    
    if (url && url.length > 5 && (url.startsWith('http') || url.startsWith('/'))) {
        preview.src = url;
        preview.onerror = function() {
            preview.src = PLACEHOLDER_SVG; 
        };
    } else {
        preview.src = PLACEHOLDER_SVG; 
    }
}


// --- FUNÇÕES DO MODAL ---
function openEditModal(role, data) {
    document.getElementById('editModal').style.display = 'block';

    // 1. Campos Hidden e Título
    document.getElementById('idInput').value = data.id || ''; 
    document.getElementById('roleInput').value = role;
    document.getElementById('modalTitle').textContent = `Editar Perfil (${role === 'tutor' ? 'Tutor' : 'Veterinário'}): ${data.nome || ''}`;

    // user_id
    document.getElementById('userIdInput').value = data.user_id || (role === 'tutor' ? data.id : ''); 


    // 2. Campos Comuns
    document.getElementById('nome').value = data.nome || '';
    document.getElementById('email').value = data.email || '';
    document.getElementById('telefone').value = data.telefone || '';
    document.getElementById('endereco').value = data.endereco || '';
    document.getElementById('senha').value = ''; 
    document.getElementById('pet_primario').value = data.pet_primario || '';
    document.getElementById('cor_favorita').value = data.cor_favorita || '';

    // 3. Gerenciar Foto (Preview)
    const fotoFile = document.getElementById('foto_file');
    const fotoUrlCurrent = document.getElementById('foto_url_current');
    const currentFotoUrl = data.foto_url || '';

    fotoFile.value = ''; 
    fotoUrlCurrent.value = currentFotoUrl; 
    updatePhotoPreview(currentFotoUrl); 

    // Adiciona listener para preview em tempo real (se ainda não tiver)
    if (!fotoFile.hasAttribute('data-listener-added')) {
        fotoFile.addEventListener('change', (e) => {
            updatePhotoPreviewFromFile(e.target);
        });
        fotoFile.setAttribute('data-listener-added', 'true');
    }

    // 4. Gerenciar visibilidade dos campos específicos do Veterinário
    const vetFields = document.getElementById('vetFields');
    
    if (role === 'vet') {
        vetFields.style.display = 'grid'; 

        // Preenche campos do Veterinário
        document.getElementById('cpf').value = data.cpf || '';
        document.getElementById('crmv').value = data.crmv || '';
        document.getElementById('nome_clinica').value = data.nome_clinica || '';
        document.getElementById('tempo_experiencia').value = data.tempo_experiencia || '';
        document.getElementById('cep_clinica').value = data.cep_clinica || '';
        document.getElementById('bairro_clinica').value = data.bairro_clinica || '';
        document.getElementById('numero_clinica').value = data.numero_clinica || '';
    } else {
        vetFields.style.display = 'none';
    }
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target === modal) {
        closeEditModal();
    }
}

// --- INICIALIZAÇÃO e Esconder Mensagem Flash ---
document.addEventListener('DOMContentLoaded', () => {
    // Inicializa a função de filtro de busca
    filterUsers(); 

    // Funcionalidade de Esconder a Mensagem Flash
    const alertElement = document.querySelector('.alert');
    if (alertElement) {
        setTimeout(() => {
            alertElement.classList.add('hide'); // Adiciona classe de transição
            
            // Remove o elemento do DOM após a transição (0.5s)
            setTimeout(() => {
                alertElement.remove();
            }, 500); 

        }, 5000); // 5 segundos
    }
});