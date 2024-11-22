<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Arquivos e Playlists</title>
    <link rel="stylesheet" href="/styles/styles.css">
    <link rel="stylesheet" href="/styles/gerenciar_playlists.css">
    <link rel="stylesheet" href="/styles/ver_playlist.css">
    <script src="script.js" type="text/javascript" charset="utf-8" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .excluir-link {
            background-color: red;
            padding: .3rem .6rem;
            border: none;
            border-radius: 10px;
            color: white;
            transition: .3s;
        }

        .excluir-link:hover {
            background-color: rgb(150, 3, 3);
        }

        .btn-delete {
            background-color: red;
            padding: .3rem .6rem;
            border: none;
            border-radius: 10px;
            color: white;
            transition: .3s;
        }

        .btn-delete:hover {
            background-color: rgb(150, 3, 3);
        }

        .arquivo {
            margin: 10px 0;
        }

        .files-section {
            display: none;
            /* Inicialmente oculto */
            margin-left: 20px;
            /* Indentação para a seção de arquivos */
        }

        .toggle-button {
            cursor: pointer;
            color: #f0f0f0;
            /* cor de banco */
            text-decoration: none;
            /* remove o sublinhado */
            text-align: center;
            /* centraliza o texto */
            display: block;
            /* faz o link se comportar como um bloco */
            padding: 10px;
            /* adiciona algum preenchimento */
            transition: background-color 0.3s ease, transform 0.3s ease;
            /* animação de hover */
        }

        .toggle-button:hover {
            background-color: rgba(255, 255, 255, 0.1);
            /* cor de fundo ao passar o mouse */
            transform: scale(1.05);
            /* aumenta levemente o tamanho */
            color: white;
        }
        
        .centralizacao_meio {
            color: white;
            text-align: center;
            margin: auto;
        }
        
        .divisor_itens {
            margin-top: 30px;
        }
        
        .div_opcoes {
            width: 50%;
            border-bottom: 5px solid black;
            border-radius: 10px;
            background-color: white;
            color: black;
            font-size: 20px;
            margin: auto;
        }
        
        #playlist {
            height: 35px;
        }
    </style>
</head>

<body>
    <header class="barra_superior">
        <div class="div_btn_voltar">
            <a href="/pages/menu.html" class="btn_voltar">Voltar</a>
        </div>
        <h2 class="centralizacao_meio">Gerenciar Arquivos e Playlists</h2>
        <div class="playlist-select centralizacao_dir">
            <h3>Playlists</h3>
            <select id="playlist" name="playlist" required>
                <?php include 'get_playlists.php'; ?>
            </select>
            <div class="btn-add-teste">
                <button type="button" id="add_to_playlist">Adicionar à Playlist</button>
            </div>
        </div>
    </header>

    <main>
        <form id="arquivos-form">
            <section>

                <div class="divisor_itens">
                    <div class="toggle-button div_opcoes" onclick="toggleSection('imagens')">Imagens</div>
                    <div class="files-section" id="imagens">
                        <?php
            // Código para obter arquivos de imagem do banco de dados
            ini_set('memory_limit', '1024M');
            $conn = new mysqli("localhost", "nexusview", "AJezEewFKGRbSR7m", "nexusview");
            if ($conn->connect_error) {
              die("Falha na conexão: " . $conn->connect_error);
            }

            $sql = "SELECT id, nome_arquivo, tipo_arquivo FROM arquivos WHERE tipo_arquivo LIKE 'image/%'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
              while ($arquivo = $result->fetch_assoc()) {
                $id_arquivo = intval($arquivo["id"]);
                $nome_arquivo = htmlspecialchars($arquivo["nome_arquivo"] ?? 'Arquivo sem nome');
                echo "<div class='arquivo'>";
                echo "<div class='checkbox-wrapper-12'>";
                echo "<div class='cbx'>";
                echo "<input type='checkbox' name='arquivo_ids[]' value='$id_arquivo'>";
                echo "<label for='cbx-$id_arquivo'></label>";
                echo "<svg fill='none' viewBox='0 0 15 14' height='14' width='15'><path d='M2 8.36364L6.23077 12L13 2'></path></svg>";
                echo "</div>";
                echo "</div>";
                echo "<a href='/API/visualizar_imagem.php?id=$id_arquivo' target='_blank'><img src='/API/visualizar_imagem.php?id=$id_arquivo' alt='Imagem'></a>";
                //echo "<p>$nome_arquivo</p>";
                echo "</div>";
                echo "<button class='excluir-link' data-id='$id_arquivo'>Excluir</button>";
              }
            } else {
              echo "<p>Sem imagens disponíveis.</p>";
            }
            ?>
                    </div>
                </div>

                <div class="divisor_itens">
                    <div class="toggle-button div_opcoes" onclick="toggleSection('videos')">Vídeos</div>
                    <div class="files-section" id="videos">
                        <?php
            // Código para obter arquivos de vídeo do banco de dados
            $sql = "SELECT id, nome_arquivo, tipo_arquivo FROM arquivos WHERE tipo_arquivo LIKE 'video/%'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
              while ($arquivo = $result->fetch_assoc()) {
                $id_arquivo = intval($arquivo["id"]);
                $nome_arquivo = htmlspecialchars($arquivo["nome_arquivo"] ?? 'Arquivo sem nome');
                echo "<div class='arquivo'>";
                echo "<div class='checkbox-wrapper-12'>";
                echo "<div class='cbx'>";
                echo "<input type='checkbox' name='arquivo_ids[]' value='$id_arquivo'>";
                echo "<label for='cbx-$id_arquivo'></label>";
                echo "<svg fill='none' viewBox='0 0 15 14' height='14' width='15'><path d='M2 8.36364L6.23077 12L13 2'></path></svg>";
                echo "</div>";
                echo "</div>";
                echo "<video width='320' height='240' controls preload='none'>
                        <source src='/API/visualizar_video.php?id=$id_arquivo' type='" . htmlspecialchars($arquivo["tipo_arquivo"]) . "'>
                        Seu navegador não suporta a tag de vídeo.
                      </video>";
                //echo "<p>$nome_arquivo</p>";
                echo "</div>";
                echo "<button class='excluir-link' data-id='$id_arquivo'>Excluir</button>";
              }
            } else {
              echo "<p>Sem vídeos disponíveis.</p>";
            }
            ?>
                    </div>
                </div>

                <div class="divisor_itens">
                    <div class="toggle-button div_opcoes" onclick="toggleSection('textos')">Textos</div>
                    <div class="files-section" id="textos">
                        <?php
            // Código para obter arquivos de texto do banco de dados
            $sql = "SELECT id, nome_arquivo, tipo_arquivo FROM arquivos WHERE tipo_arquivo LIKE 'text/%'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
              while ($arquivo = $result->fetch_assoc()) {
                $id_arquivo = intval($arquivo["id"]);
                $nome_arquivo = htmlspecialchars($arquivo["nome_arquivo"] ?? 'Arquivo sem nome');
                echo "<div class='arquivo'>";
                echo "<div class='checkbox-wrapper-12'>";
                echo "<div class='cbx'>";
                echo "<input type='checkbox' name='arquivo_ids[]' value='$id_arquivo'>";
                echo "<label for='cbx-$id_arquivo'></label>";
                echo "<svg fill='none' viewBox='0 0 15 14' height='14' width='15'><path d='M2 8.36364L6.23077 12L13 2'></path></svg>";
                echo "</div>";
                echo "</div>";
                echo "<a href='visualizar_texto.php?id=$id_arquivo' target='_blank'>Ver arquivo de texto</a>";
                //echo "<p>$nome_arquivo</p>";
                echo "</div>";
                echo "<button class='excluir-link' data-id='$id_arquivo'>Excluir</button>";
              }
            } else {
              echo "<p>Sem arquivos de texto disponíveis.</p>";
            }
            ?>
                    </div>
                </div>

                <div class="divisor_itens">
                    <div class="toggle-button div_opcoes" onclick="toggleSection('playlists')">Playlists</div>
                    <div class="files-section" id="playlists" style="display: none;"> <!-- Inicialmente escondido -->
                        <div class="container">
                            <?php
                // Selecionar playlists
                $sql = "SELECT * FROM playlists";
                $result = $conn->query($sql);
    
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='playlist' id='playlist-" . intval($row["id"]) . "'>";
                        echo "<h2 style='color: white;'>" . htmlspecialchars($row["nome"]) . "</h2>";
                        echo "<p style='color: white;'>" . htmlspecialchars($row["descricao"]) . "</p>";
    
                        $playlist_id = intval($row["id"]);
                        $sql_arquivos = "SELECT a.* FROM arquivos a JOIN playlist_arquivos pa ON a.id = pa.arquivo_id WHERE pa.playlist_id=" . $playlist_id;
                        $result_arquivos = $conn->query($sql_arquivos);
    
                        if ($result_arquivos->num_rows > 0) {
                            echo "<div class='arquivos'>";
                            while ($arquivo = $result_arquivos->fetch_assoc()) {
                                echo "<div class='arquivo'>";
    
                                $tipo_arquivo = htmlspecialchars($arquivo["tipo_arquivo"]);
                                $id_arquivo = intval($arquivo["id"]);
    
                                if (strpos($tipo_arquivo, 'image/') === 0) {
                                    // Para arquivos de imagem
                                    echo "<a href='/API/visualizar_imagem.php?id=$id_arquivo' target='_blank'>";
                                    echo "<img src='/API/visualizar_imagem.php?id=$id_arquivo' alt='Imagem'>";
                                    echo "</a>";
                                    echo "<button class='btn-delete' onclick='excluirArquivo(this, $playlist_id); event.stopPropagation();' data-id='$id_arquivo'>Excluir</button>";
                                } elseif (strpos($tipo_arquivo, 'video/') === 0) {
                                    // Para arquivos de vídeo
                                    echo "<video width='320' height='240' controls>";
                                    echo "<source src='visualizar_video.php?id=$id_arquivo' type='$tipo_arquivo'>";
                                    echo "Seu navegador não suporta a tag de vídeo.";
                                    echo "</video>";
                                    echo "<button class='btn-delete' onclick='excluirArquivo(this, $playlist_id); event.stopPropagation();' data-id='$id_arquivo'>Excluir</button>";
                                } elseif (strpos($tipo_arquivo, 'text/plain') === 0 || strpos($tipo_arquivo, 'text/html') === 0) {
                                    // Para arquivos de texto e HTML, com pré-visualização
                                    echo "<a href='visualizar_texto.php?id=$id_arquivo' target='_blank'>";
                                    echo "Ver arquivo de texto";
                                    echo "</a>";
                                    echo "<button class='btn-delete' onclick='excluirArquivo(this, $playlist_id); event.stopPropagation();' data-id='$id_arquivo'>Excluir</button>";
    
                                    // Mostrar pré-visualização (limitar a 200 caracteres)
                                    $conteudo_resumido = htmlspecialchars(substr($arquivo["conteudo"], 0, 200));
                                    echo "<div class='tooltip'>" . $conteudo_resumido . "...</div>";
                                } else {
                                    echo "<p>Tipo de arquivo desconhecido</p>";
                                }
    
                                echo "</div>"; // Fechar div .arquivo
                            }
                            echo "</div>"; // Fechar div .arquivos
                        } else {
                            echo "<p>Sem arquivos nesta playlist.</p>";
                        }
    
                        echo "</div>"; // Fechar div .playlist
                        echo "<hr>"; // Linha horizontal para separar as playlists
                    }
                } else {
                    echo "<p>Sem playlists disponíveis.</p>";
                }
    
                $conn->close();
                ?>
                        </div>
                    </div>
                </div>

            </section>
        </form>
    </main>

    <script>
        function excluirArquivo(button, playlistId) {
            // Obter o ID do arquivo
            event.preventDefault()
            var idArquivo = button.getAttribute('data-id');
            var arquivoDiv = button.parentElement; // Obter o elemento pai do botão

            // Fazer uma requisição fetch para desvincular o arquivo da playlist
            fetch('/API/exclui_arquivo_playlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + encodeURIComponent(idArquivo) + '&playlist_id=' + encodeURIComponent(playlistId)
            })
                .then(response => response.text()) // Ler a resposta como texto
                .then(message => {
                    if (message.includes('sucesso')) {
                        // Se a desvinculação foi bem-sucedida, ocultar o elemento
                        arquivoDiv.style.display = 'none'; // Remover da visualização

                        // Mostrar mensagem de sucesso
                        Swal.fire({
                            title: 'Sucesso!',
                            text: message,
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        });
                    } else {
                        // Se ocorreu um erro, mostrar mensagem
                        Swal.fire({
                            title: 'Erro!',
                            text: message,
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }
                })
                .catch(error => {
                    // Se houver um erro na requisição, exibe uma mensagem de erro
                    Swal.fire({
                        title: 'Erro!',
                        text: 'Ocorreu um erro na requisição.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                    console.error('Erro:', error);
                });
        }

        function toggleSection(sectionId) {
            const section = document.getElementById(sectionId);
            if (section.style.display === 'none' || section.style.display === '') {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        }

        document.getElementById('add_to_playlist').addEventListener('click', function () {
            const playlistId = document.getElementById('playlist').value;
            const selectedFiles = Array.from(document.querySelectorAll('input[name="arquivo_ids[]"]:checked')).map(checkbox => checkbox.value);

            if (selectedFiles.length === 0) {
                alert('Selecione pelo menos um arquivo.');
                return;
            }

            if (!playlistId) {
                alert('Selecione uma playlist.');
                return;
            }

            fetch('/API/adicionar_playlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'playlist_id=' + encodeURIComponent(playlistId) + '&arquivo_ids=' + encodeURIComponent(JSON.stringify(selectedFiles))
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Algum erro ocorreu e não foi possível adicionar o arquivo!',
                            icon: 'error',
                            toast: true, // Exibe uma mensagem de sucesso como um toast
                            position: 'top-right', // Posição da mensagem
                            timer: 4000, // Fecha a mensagem após 2 segundos
                            showConfirmButton: false // Não exibe o botão "OK"
                        });
                    } else {
                        Swal.fire({
                            title: 'Sucesso!',
                            text: 'Arquivo adicionado!',
                            icon: 'success',
                            toast: true, // Exibe uma mensagem de sucesso como um toast
                            position: 'top-right', // Posição da mensagem
                            timer: 4000, // Fecha a mensagem após 2 segundos
                            showConfirmButton: false // Não exibe o botão "OK"
                        });
                    }
                })
                .catch(error => console.error('Erro:', error));
        });

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.excluir-link').forEach(function (link) {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    if (confirm('Tem certeza que deseja excluir este arquivo?')) {
                        const id = this.getAttribute('data-id');
                        const item = this.closest('.arquivo');
                        fetch('/API/excluir_arquivo.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: 'id=' + id
                        })
                            .then(response => response.json())
                            .then(data => {
                                console.log(data);
                                if (data.status === 'success') {
                                    Swal.fire({
                                        title: 'Sucesso!',
                                        text: 'Arquivo excluído!',
                                        icon: 'success',
                                        toast: true,
                                        position: 'top-right',
                                        timer: 4000, // Fecha a mensagem após 4 segundos
                                        showConfirmButton: false
                                    });
                                    setTimeout(function () {
                                        window.location.reload();
                                    }, 1000);
                                } else {
                                    Swal.fire({
                                        title: 'Erro!',
                                        text: 'Algum erro ocorreu e não foi possível excluir o arquivo!',
                                        icon: 'error',
                                        toast: true,
                                        position: 'top-right',
                                        timer: 4000, // Fecha a mensagem após 4 segundos
                                        showConfirmButton: false
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Erro:', error);
                            });
                    }
                });
            });
        });
    </script>
</body>

</html>