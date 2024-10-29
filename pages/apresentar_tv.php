<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Apresentação</title>
    <link rel="stylesheet" href="/styles/styles.css">
    <link rel="stylesheet" href="/styles/apresentacao_tv.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="div_btn_voltar">
        <a href="./menu.html" class="btn_voltar">Voltar</a>
    </div>

    <div class="container_apresentacao">
        <div class="form_apresentacao">
            <h2 class="title_apresentacao">Iniciar Apresentação</h2>
            <form id="form" action="#" method="POST" class="form_apresentacao">
                <label for="playlist" class="title_apresentacao">Selecione a Playlist:</label>
                <select id="playlist" class="form-select mb-3" name="playlist" required>
                    <?php include 'preencher_playlist.php'; ?>
                </select>
                
                <label for="dispositivo" class="title_apresentacao">Selecione o Dispositivo:</label>
                <select id="dispositivo" class="form-select" name="dispositivo" required>
                    <?php include 'preencher_dispositivo.php'; ?>
                </select>
    
                <input type="submit" class="btn_cadastro" value="Iniciar Apresentação">
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('form').addEventListener('submit', function (event) {
                event.preventDefault();

                const playlistId = document.getElementById('playlist').value;
                const dispositivoId = document.getElementById('dispositivo').value;

                // Redirecionar para a nova página com os parâmetros na URL
                window.open(`apresentacao.html?playlist_id=${playlistId}&dispositivo_id=${dispositivoId}`, '_blank');
            });
        });
    </script>
</body>

</html>
