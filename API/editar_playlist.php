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
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];

    // Preparar a declaração SQL para atualizar a playlist
    $sql = "UPDATE playlists SET nome=?, descricao=? WHERE id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nome, $descricao, $id);

    if ($stmt->execute()) {
        // Retornar uma resposta de sucesso
        echo json_encode(["success" => true]);
    } else {
        // Retornar uma resposta de erro
        echo json_encode(["success" => false, "message" => "Erro ao atualizar a playlist."]);
    }

    $stmt->close();
}

$conn->close();
?>