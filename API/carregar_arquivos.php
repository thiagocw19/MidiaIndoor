<?php
// carregar_arquivos.php
header('Content-Type: application/json');

$tipo = $_GET['tipo'];
$offset = intval($_GET['offset']);
$limit = intval($_GET['limit']);

// Conexão com o banco de dados
$conn = new mysqli("localhost", "nexusview", "AJezEewFKGRbSR7m", "nexusview");
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Consulta SQL para obter os arquivos com base no tipo
$sql = "SELECT id, nome_arquivo, tipo_arquivo FROM arquivos WHERE tipo_arquivo LIKE '$tipo/%' LIMIT $offset, $limit";
$result = $conn->query($sql);

$arquivos = [];
if ($result->num_rows > 0) {
    while ($arquivo = $result->fetch_assoc()) {
        $arquivos[] = $arquivo;
    }
}

// Retornar os arquivos como JSON
echo json_encode($arquivos);
$conn->close();
?>