<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Playlists</title>
    <link rel="stylesheet" href="/styles/styles.css">
    <link rel="stylesheet" href="/styles/ver_playlist.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        .btn-delete {
            background-color: red;
            padding: .3rem .6rem;
            border: none;
            border-radius: 10px;
            color: white;
            transition: .3s;
        }
        
        .btn-delete:hover {
            background-color: green;
        }
    </style>
</head>

<body>
    <div class="div_btn_voltar">
        <a href="./menu.html" class="btn_voltar">Voltar</a>
    </div>
    <h1 class="title_visualizar">Visualizar Playlists</h1>
    <div class="container">
        <?php
        $servername = "localhost";
        $username = "nexusview";
        $password = "AJezEewFKGRbSR7m";
        $dbname = "nexusview";

        // Criar conexão
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Checar conexão
        if ($conn->connect_error) {
            die("Falha na conexão: " . $conn->connect_error);
        }

        // Selecionar playlists
        $sql = "SELECT * FROM playlists";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='playlist'>";
                echo "<h2>" . htmlspecialchars($row["nome"]) . "</h2>";
                echo "<p>" . htmlspecialchars($row["descricao"]) . "</p>";

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
                            echo "<button class='btn-delete' onclick='excluirArquivo(this, $playlist_id)' data-id='$id_arquivo'>Excluir</button>";
                        } elseif (strpos($tipo_arquivo, 'video/') === 0) {
                            // Para arquivos de vídeo
                            echo "<video width='320' height='240' controls>";
                            echo "<source src='visualizar_video.php?id=$id_arquivo' type='$tipo_arquivo'>";
                            echo "Seu navegador não suporta a tag de vídeo.";
                            echo "</video>";
                        } elseif (strpos($tipo_arquivo, 'text/plain') === 0 || strpos($tipo_arquivo, 'text/html') === 0) {
                            // Para arquivos de texto e HTML, com pré-visualização
                            echo "<a href='visualizar_texto.php?id=$id_arquivo' target='_blank'>";
                            echo "Ver arquivo de texto";
                            echo "</a>";

                            // Mostrar pré-visualização (limitar a 200 caracteres)
                            $conteudo_resumido = htmlspecialchars(substr($arquivo["conteudo"], 0, 200));
                            echo "<div class='tooltip'>" . $conteudo_resumido . "...</div>";
                        } else {
                            echo "<p>Tipo de arquivo desconhecido</p>";
                        }

                        // echo "<a href='excluir_arquivo.php?id=" . intval($arquivo["id"]) . "' class='excluir-link'>Excluir</a><br>";
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

    <script>
        function excluirArquivo(button, playlistId) {
    // Obter o ID do arquivo
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

    </script>



</body>

</html>