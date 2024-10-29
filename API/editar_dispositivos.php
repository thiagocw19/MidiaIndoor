<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "nexusview";
$password = "AJezEewFKGRbSR7m";
$dbname = "nexusview";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verificar se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $modelo = $_POST['modelo'];
    $resolucao = $_POST['resolucao'];

    // Preparar a declaração SQL para atualizar a playlist
    $sql = "UPDATE dispositivos SET modelo=?, resolucao=? WHERE id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $modelo, $resolucao, $id);

    if ($stmt->execute()) {
        // Retornar uma resposta de sucesso
        echo json_encode(["success" => true]);
    } else {
        // Retornar uma resposta de erro
        echo json_encode(["success" => false, "message" => "Erro ao atualizar o dispositivo."]);
    }

    $stmt->close();
}

$conn->close();
?>