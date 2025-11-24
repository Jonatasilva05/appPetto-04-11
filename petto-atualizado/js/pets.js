
    function filterTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toUpperCase();
        const table = document.getElementById('petsTable');
        const tr = table.getElementsByTagName('tr');

        // Itera sobre todas as linhas (começando em 1 para ignorar o cabeçalho)
        for (let i = 1; i < tr.length; i++) {
            let row = tr[i];
            let display = 'none';
            // Colunas de busca: ID (0), Nome do Pet (1), Tutor (3)
            const colsToSearch = [0, 1, 3]; 
            
            for (let j = 0; j < colsToSearch.length; j++) {
                const cell = row.getElementsByTagName('td')[colsToSearch[j]];
                if (cell) {
                    const textValue = cell.textContent || cell.innerText;
                    if (textValue.toUpperCase().indexOf(filter) > -1) {
                        display = ''; 
                        break;
                    }
                }
            }
            row.style.display = display;
        }
    }