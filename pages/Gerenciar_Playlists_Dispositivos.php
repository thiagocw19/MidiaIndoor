<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Playlists e Dispositivos</title>
    <link rel="stylesheet" href="/styles/styles.css">
    <link rel="stylesheet" href="/styles/gerenciar_playlists.css">
    <script>
        // Função para carregar o formulário de edição via AJAX
        function carregarFormularioEdicao(dispositivo_id, playlist_id) {
            document.getElementById('dispositivo').value = dispositivo_id; // Preenche o dispositivo
            document.getElementById('playlists').value = playlist_id; // Preenche a playlist
            document.getElementById('vincular-form').setAttribute('data-editing', 'true'); // Define que está editando
        }

        // Função para verificar se um dispositivo já possui uma playlist vinculada
        function verificarVinculo() {
            var dispositivo_id = document.getElementById('dispositivo').value;
            var isEditing = document.getElementById('vincular-form').getAttribute('data-editing') === 'true'; // Verifica se está editando

            var xhr = new XMLHttpRequest();
            xhr.open("GET", "verificar_vinculo.php?dispositivo_id=" + dispositivo_id, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var resposta = JSON.parse(xhr.responseText);
                    // Se está editando, não precisa verificar se já está vinculado
                    if (resposta.vinculado && !isEditing) {
                        alert("Este dispositivo já possui uma playlist vinculada. Apenas edite!");
                        return false; // Evitar salvar
                    } else {
                        // Se não estiver vinculado, prosseguir com a associação
                        var playlist_id = document.getElementById('playlists').value;
                        salvarAssociacao(dispositivo_id, playlist_id, isEditing);
                    }
                }
            };
            xhr.send();
            return false; // Evitar o envio padrão do formulário
        }

        // Função para salvar a associação
        function salvarAssociacao(dispositivo_id, playlist_id, isEditing) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "salvar_associacao.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    location.reload(); // Recarregar a página para ver as atualizações
                }
            };
            // Envia o ID do dispositivo e da playlist, além de indicar se é edição
            xhr.send("dispositivo_id=" + dispositivo_id + "&playlist_id=" + playlist_id + "&edit=" + isEditing);
        }

        // Função para excluir o vínculo do dispositivo com a playlist
        function excluirVinculo(dispositivo_id) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "editar_vinculo.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    location.reload(); // Recarregar a página para ver as atualizações
                }
            };
            xhr.send("dispositivo_id=" + dispositivo_id + "&excluir_vinculo=true");
        }
    </script>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 50%;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }

        button {
            background: #28a745;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            transition: background 0.3s;
        }

        button:hover {
            background: #218838;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: #fff;
            border-radius: 5px;
            overflow: hidden;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #f2f2f2;
        }

        tr:hover {
            background: #f9f9f9;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            /* Espaçamento entre os botões */
        }
    </style>
</head>

<body>

    <div class="div_btn_voltar">
        <a href="./menu.html" class="btn_voltar">Voltar</a>
    </div>

    <h2>Gerenciar Playlists e Dispositivos</h2>

    <form id="vincular-form" method="POST" action="" onsubmit="event.preventDefault(); verificarVinculo();" data-editing="false">
        <h3>Selecione o dispositivo e a playlist:</h3>

        <label for="dispositivo">Dispositivo:</label>
        <select id="dispositivo" name="dispositivo" required>
            <?php
            // Conectar ao banco de dados
            $conn = new mysqli("localhost", "nexusview", "AJezEewFKGRbSR7m", "nexusview");

            if ($conn->connect_error) {
                die("Conexão falhou: " . $conn->connect_error);
            }

            // Selecionar todos os dispositivos
            $sql = "SELECT id, tipo, modelo FROM dispositivos";
            $result = $conn->query($sql);

            while ($dispositivo = $result->fetch_assoc()) {
                echo "<option value='" . $dispositivo['id'] . "'>" . $dispositivo['tipo'] . " - " . $dispositivo['modelo'] . "</option>";
            }
            ?>
        </select>

        <label for="playlists">Playlist:</label>
        <select id="playlists" name="playlists" required>
            <?php include 'get_playlists.php'; ?>
        </select>

        <button type="submit" name="submit">Salvar Associação</button>
    </form>

    <?php
    // Exibir as associações que têm playlist
    echo "<h3>Dispositivos já vinculados a playlists:</h3>";
    echo "<table>";
    echo "<tr><th>Dispositivo</th><th>Playlist</th><th>Ação</th></tr>";

    // Selecionar apenas dispositivos que têm uma playlist associada
    $sql = "SELECT dispositivos.id AS dispositivo_id, dispositivos.tipo, dispositivos.modelo, playlists.id AS playlist_id, playlists.nome AS playlist_nome
            FROM dispositivos
            LEFT JOIN playlists ON dispositivos.playlist_id = playlists.id
            WHERE dispositivos.playlist_id IS NOT NULL";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['tipo'] . " - " . $row['modelo'] . "</td>";
        echo "<td>" . $row['playlist_nome'] . "</td>";
        echo "<td class='action-buttons'>
                <button onclick='carregarFormularioEdicao(" . $row['dispositivo_id'] . ", " . $row['playlist_id'] . ")'>Editar</button>
                <button onclick='excluirVinculo(" . $row['dispositivo_id'] . ")'>Excluir</button>
              </td>";
        echo "</tr>";
    }

    echo "</table>";

    $conn->close();
    ?>
</body>

</html>
