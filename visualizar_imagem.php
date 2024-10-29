<?php
$servername = "localhost";
$username = "nexusview";
$password = "AJezEewFKGRbSR7m";
$dbname = "nexusview";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT tipo_arquivo, conteudo FROM arquivos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($tipo_arquivo, $conteudo);
    
    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        
        header("Content-Type: $tipo_arquivo");
        echo $conteudo;
    } else {
        echo "Arquivo não encontrado.";
    }
    
    $stmt->close();
}

$conn->close();
?>
