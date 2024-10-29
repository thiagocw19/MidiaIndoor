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

<style>
    /* Estilos omitidos para brevidade */
</style>

<body>
    <div class="container_apresentacao">
        <div class="form_apresentacao">
            <h2 class="title_apresentacao">Iniciar Apresentação</h2>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Playlist</th>
                        <th>Dispositivo Vinculado</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Conectar ao banco de dados
                    $conn = new mysqli("localhost", "nexusview", "AJezEewFKGRbSR7m", "nexusview");

                    if ($conn->connect_error) {
                        die("Conexão falhou: " . $conn->connect_error);
                    }

                    // Selecionar playlists e dispositivos vinculados
                    $sql = "SELECT playlists.id AS playlist_id, playlists.nome AS playlist_nome, 
                                   dispositivos.id AS dispositivo_id, dispositivos.tipo, dispositivos.modelo
                            FROM playlists
                            LEFT JOIN dispositivos ON playlists.id = dispositivos.playlist_id
                            ORDER BY playlists.nome";

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['playlist_nome']) . "</td>";
                            echo "<td>" . ($row['dispositivo_id'] ? htmlspecialchars($row['tipo'] . " - " . $row['modelo']) : "Nenhum dispositivo vinculado") . "</td>";
                            echo "<td>";
                            if ($row['dispositivo_id']) {
                                // Apenas exibir o botão se houver um dispositivo vinculado
                                echo "<a href='apresentacao.html?playlist_id=" . $row['playlist_id'] . "&dispositivo_id=" . $row['dispositivo_id'] . "' class='btn btn-primary' target='_blank'>Play</a>";
                            } else {
                                echo "<span class='text-muted'>Sem Ação</span>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Nenhuma playlist encontrada.</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>

            <!-- Botão de Atualizar -->
            <div class="text-center mt-3">
                <button class="btn btn-secondary" onclick="window.location.reload();">Atualizar</button>
            </div>

        </div>
    </div>

    <script>
        // Se necessário, você pode adicionar mais scripts aqui
    </script>
</body>

</html>
