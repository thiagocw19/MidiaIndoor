<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "nexusview";
$password = "AJezEewFKGRbSR7m";
$dbname = "nexusview";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão com o banco de dados falhou: " . $conn->connect_error);
}

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Excluir arquivos associados
    $sqlDeleteFiles = "DELETE FROM playlist_arquivos WHERE playlist_id = ?";
    $stmtDeleteFiles = $conn->prepare($sqlDeleteFiles);
    $stmtDeleteFiles->bind_param("i", $id);
    $stmtDeleteFiles->execute();
    $stmtDeleteFiles->close();

    // Excluir a playlist
    $sql = "DELETE FROM playlists WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Playlist excluída com sucesso.";
        } else {
            echo "Nenhuma playlist encontrada com esse ID.";
        }
    } else {
        echo "Erro ao excluir a playlist: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "ID da playlist não fornecida.";
}
?>