function carregarDispositivos() {
    fetch('/API/obter_dispositivos.php')
        .then(response => response.json())
        .then(data => {
            const dispositivoContainer = document.getElementById('dispositivo-container');
            dispositivoContainer.innerHTML = ''; // Limpar o container antes de adicionar as playlists
            data.forEach(dispositivo => {
                const dispositivoHTML = `
            <div class="dispositivo" data-id="${dispositivo.id}">
                <label for="modelo">Modelo:</label>
                <h2 class="dispositivo-modelo">${dispositivo.modelo}}</h2>
                <input type="text" class="edit-modelo" value="${dispositivo.modelo}" style="display:none;">
                <label for="resolucao">Resolução:</label>
                <p class="dispositivo-resolucao">${dispositivo.resolucao}</p>
                <input type="text" class="edit-resolucao" value="${dispositivo.resolucao}" style="display:none;">
                <button class="btn_edit btn btn-success">Editar</button>
                <button class="btn_save btn btn-success" style="display:none;">Salvar</button>
                <button class="btn_delete btn btn-danger">Excluir</button>
            </div>
        `;
                dispositivoContainer.innerHTML += dispositivoHTML;
            });

            // Adicionar eventos de clique aos botões de edição e exclusão
            adicionarEventos();
        })
        .catch(error => console.error('Erro ao carregar dispositivos:', error));
}

function adicionarEventos() {
    // Adicionando evento de clique aos botões de edição
    const editButtons = document.querySelectorAll('.btn_edit');
    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            const dispositivoDiv = button.closest('.dispositivo');
            dispositivoDiv.querySelector('.dispositivo-modelo').style.display = 'none';
            dispositivoDiv.querySelector('.dispositivo-resolucao').style.display = 'none';
            dispositivoDiv.querySelector('.edit-modelo').style.display = 'block';
            dispositivoDiv.querySelector('.edit-resolucao').style.display = 'block';
            dispositivoDiv.querySelector('.btn_save').style.display = 'inline-block';
            button.style.display = 'none';
        });
    });

    // Adicionando evento de clique aos botões de salvamento
    const saveButtons = document.querySelectorAll('.btn_save');
    saveButtons.forEach(button => {
        button.addEventListener('click', () => {
            const dispositivoDiv = button.closest('.dispositivo');
            const dispositivoId = dispositivoDiv.dataset.id;
            const newModelo = dispositivoDiv.querySelector('.edit-modelo').value;
            const newResolucao = dispositivoDiv.querySelector('.edit-resolucao').value;

            // Enviando uma requisição para salvar as alterações
            fetch('/API/editar_dispositivos.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `id=${dispositivoId}&modelo=${newModelo}&resolucao=${newResolucao}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Atualizando a interface com os novos valores
                        dispositivoDiv.querySelector('.dispositivo-modelo').textContent = newModelo;
                        dispositivoDiv.querySelector('.dispositivo-resolucao').textContent = newResolucao;
                        dispositivoDiv.querySelector('.dispositivo-modelo').style.display = 'block';
                        dispositivoDiv.querySelector('.dispositivo-resolucao').style.display = 'block';
                        dispositivoDiv.querySelector('.edit-modelo').style.display = 'none';
                        dispositivoDiv.querySelector('.edit-resolucao').style.display = 'none';
                        dispositivoDiv.querySelector('.btn_save').style.display = 'none';
                        dispositivoDiv.querySelector('.btn_edit').style.display = 'inline-block';
                    } else {
                        // Se ocorreu um erro, exiba uma mensagem de erro
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Erro ao salvar alterações:', error));
        });
    });

    // Adicionando evento de clique aos botões de exclusão
    const deleteButtons = document.querySelectorAll('.btn_delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            const dispositivoId = button.closest('.dispositivo').dataset.id;

            // Confirmação de exclusão (opcional, mas recomendado)
            if (confirm(`Tem certeza que deseja excluir o dispositivo?`)) {
                // Enviando uma requisição para excluir a playlist
                fetch('/API/excluir_dispositivo.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${dispositivoId}`
                })
                    .then(response => response.text())
                    .then(data => {
                        console.log(data); // Verifique o que está sendo retornado
                        if (data.success) {
                            Swal.fire({
                                title: 'Sucesso!',
                                text: 'Dispositivo excluído com sucesso!',
                                icon: 'success',
                                toast: true, // Exibe uma mensagem de sucesso como um toast
                                position: 'top-right', // Posição da mensagem
                                timer: 4000, // Fecha a mensagem após 2 segundos
                                showConfirmButton: false // Não exibe o botão "OK"
                            });
                            button.closest('.dispositivo').remove();
                            carregarDispositivos();
                        } else {
                            Swal.fire({
                                title: 'Sucesso!',
                                text: 'Dispositivo excluído com sucesso!',
                                icon: 'success',
                                toast: true, // Exibe uma mensagem de sucesso como um toast
                                position: 'top-right', // Posição da mensagem
                                timer: 4000, // Fecha a mensagem após 2 segundos
                                showConfirmButton: false // Não exibe o botão "OK"
                            });
                            carregarDispositivos();
                            button.closest('.dispositivo').remove();
                        }
                    })
                    .catch(error => console.error('Erro ao excluir dispositivo:', error));
            }
        });
    });
}

document.getElementById('form_dispositivo').addEventListener('submit', function (event) {
    event.preventDefault(); // Impede o envio padrão do formulário

    const formData = new FormData(this);

    fetch('/API/cadastrar_dispositivo.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("erro");
            } else {
                Swal.fire({
                    title: 'Sucesso!',
                    text: 'dispositivo cadastrado com sucesso!',
                    icon: 'success',
                    toast: true, // Exibe uma mensagem de sucesso como um toast
                    position: 'top-right', // Posição da mensagem
                    timer: 4000, // Fecha a mensagem após 2 segundos
                    showConfirmButton: false // Não exibe o botão "OK"
                });
                this.reset(); // Limpa o formulário
                carregarDispositivos(); // Atualiza a lista de playlists
            }
        })
        .catch(error => console.error('Erro ao cadastrar o dispositivo:', error));
});

// Carregar as playlists ao carregar a página
carregarDispositivos();