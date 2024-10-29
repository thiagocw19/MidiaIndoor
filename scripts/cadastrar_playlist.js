function carregarPlaylists() {
  fetch('/API/obter_playlist.php')
    .then(response => response.json())
    .then(data => {
      const playlistContainer = document.getElementById('playlist-container');
      playlistContainer.innerHTML = ''; // Limpar o container antes de adicionar as playlists
      data.forEach(playlist => {
        const playlistHTML = `
          <div class="playlist" data-id="${playlist.id}">
            <label for="nome">Nome:</label>
            <h2 class="playlist-nome">${playlist.nome}</h2>
            <input type="text" class="edit-nome" value="${playlist.nome}" style="display:none;">
            <label for="descricao">Descrição:</label>
            <p class="playlist-descricao">${playlist.descricao}</p>
            <textarea class="edit-descricao" style="display:none;">${playlist.descricao}</textarea>
            <button class="btn_edit btn btn-success">Editar</button>
            <button class="btn_save btn btn-success" style="display:none;">Salvar</button>
            <button class="btn_delete btn btn-danger">Excluir</button>
          </div>
        `;
        playlistContainer.innerHTML += playlistHTML;
      });

      // Adicionar eventos de clique aos botões de edição e exclusão
      adicionarEventos();
    })
    .catch(error => console.error('Erro ao carregar playlists:', error));
}

function adicionarEventos() {
    // Adicionando evento de clique aos botões de edição
    const editButtons = document.querySelectorAll('.btn_edit');
    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            const playlistDiv = button.closest('.playlist');
            playlistDiv.querySelector('.playlist-nome').style.display = 'none';
            playlistDiv.querySelector('.playlist-descricao').style.display = 'none';
            playlistDiv.querySelector('.edit-nome').style.display = 'block';
            playlistDiv.querySelector('.edit-descricao').style.display = 'block';
            playlistDiv.querySelector('.btn_save').style.display = 'inline-block';
            button.style.display = 'none';
        });
    });

    // Adicionando evento de clique aos botões de salvamento
    const saveButtons = document.querySelectorAll('.btn_save');
    saveButtons.forEach(button => {
        button.addEventListener('click', () => {
            const playlistDiv = button.closest('.playlist');
            const playlistId = playlistDiv.dataset.id;
            const newNome = playlistDiv.querySelector('.edit-nome').value;
            const newDescricao = playlistDiv.querySelector('.edit-descricao').value;

            // Enviando uma requisição para salvar as alterações
            fetch('/API/editar_playlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `id=${playlistId}&nome=${newNome}&descricao=${newDescricao}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Atualizando a interface com os novos valores
                        playlistDiv.querySelector('.playlist-nome').textContent = newNome;
                        playlistDiv.querySelector('.playlist-descricao').textContent = newDescricao;
                        playlistDiv.querySelector('.playlist-nome').style.display = 'block';
                        playlistDiv.querySelector('.playlist-descricao').style.display = 'block';
                        playlistDiv.querySelector('.edit-nome').style.display = 'none';
                        playlistDiv.querySelector('.edit-descricao').style.display = 'none';
                        playlistDiv.querySelector('.btn_save').style.display = 'none';
                        playlistDiv.querySelector('.btn_edit').style.display = 'inline-block';
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
            const playlistId = button.closest('.playlist').dataset.id;

            // Confirmação de exclusão (opcional, mas recomendado)
            if (confirm(`Tem certeza que deseja excluir a?`)) {
                // Enviando uma requisição para excluir a playlist
                fetch('/API/excluir_playlist.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${playlistId}`
                })
                    .then(response => response.text())
                    .then(data => {
                        console.log(data); // Verifique o que está sendo retornado
                        if (data.success) {
                            Swal.fire({
                                title: 'Sucesso!',
                                text: 'Playlist excluída com sucesso!',
                                icon: 'success',
                                toast: true, // Exibe uma mensagem de sucesso como um toast
                                position: 'top-right', // Posição da mensagem
                                timer: 4000, // Fecha a mensagem após 2 segundos
                                showConfirmButton: false // Não exibe o botão "OK"
                            });
                            button.closest('.playlist').remove();
                            carregarPlaylists();
                        } else {
                            Swal.fire({
                                title: 'Sucesso!',
                                text: 'Playlist excluída com sucesso!',
                                icon: 'success',
                                toast: true, // Exibe uma mensagem de sucesso como um toast
                                position: 'top-right', // Posição da mensagem
                                timer: 4000, // Fecha a mensagem após 2 segundos
                                showConfirmButton: false // Não exibe o botão "OK"
                            });
                            carregarPlaylists();
                            button.closest('.playlist').remove();
                        }
                    })
                    .catch(error => console.error('Erro ao excluir playlist:', error));
            }
        });
    });
}


document.getElementById('form_playlist').addEventListener('submit', function (event) {
    event.preventDefault(); // Impede o envio padrão do formulário

    const formData = new FormData(this);

    fetch('/API/cadastrar_playlist.php', {
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
                    text: 'Playlist cadastrada com sucesso!',
                    icon: 'success',
                    toast: true, // Exibe uma mensagem de sucesso como um toast
                    position: 'top-right', // Posição da mensagem
                    timer: 4000, // Fecha a mensagem após 2 segundos
                    showConfirmButton: false // Não exibe o botão "OK"
                });
                this.reset(); // Limpa o formulário
                carregarPlaylists(); // Atualiza a lista de playlists
            }
        })
        .catch(error => console.error('Erro ao cadastrar a playlist:', error));
});

// Carregar as playlists ao carregar a página
carregarPlaylists();