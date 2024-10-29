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

    // Excluir a playlist
    $sql = "DELETE FROM dispositivos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Dispositivo excluído com sucesso.";
        } else {
            echo "Nenhum dispositivo encontrado com esse ID.";
        }
    } else {
        echo "Erro ao excluir o dispositivo: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "ID do dispositivo não fornecido.";
}
?>