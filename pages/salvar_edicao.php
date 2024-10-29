<?php
// Conectar ao banco de dados
$conn = new mysqli("localhost", "nexusview", "AJezEewFKGRbSR7m", "nexusview");

// Verificar se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar se os dados necessários foram enviados
    if (isset($_POST['dispositivo_id']) && isset($_POST['playlists'])) {
        $dispositivo_id = $_POST['dispositivo_id'];
        $playlist_id = $_POST['playlists'];

        // Atualizar a associação no banco de dados
        $update_sql = "UPDATE dispositivos SET playlist_id = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ii", $playlist_id, $dispositivo_id);
        $stmt->execute();

        // Verificar se a atualização foi bem-sucedida
        if ($stmt->affected_rows > 0) {
            // Redirecionar de volta para a página principal (ou a página desejada)
            header("Location: Gerenciar_Playlists_Dispositivos.php?mensagem=Associação%20atualizada%20com%20sucesso!");
            exit();
        } else {
            // Redirecionar de volta com uma mensagem de erro
            header("Location: Gerenciar_Playlists_Dispositivos.php?mensagem=Erro%20ao%20atualizar%20a%20associação.");
            exit();
        }
    } else {
        // Redirecionar se os dados não forem enviados
        header("Location: Gerenciar_Playlists_Dispositivos.php?mensagem=Dado%20do%20dispositivo%20ou%20playlist%20não%20encontrados.");
        exit();
    }
} else {
    // Redirecionar se o método de requisição for inválido
    header("Location: Gerenciar_Playlists_Dispositivos.php?mensagem=Método%20de%20requisição%20inválido.");
    exit();
}

// Fechar a conexão
$conn->close();
?>
