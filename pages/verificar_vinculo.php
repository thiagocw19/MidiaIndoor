<?php
header('Content-Type: application/json');

$dispositivo_id = $_GET['dispositivo_id'];

// Conectar ao banco de dados
$conn = new mysqli("localhost", "nexusview", "AJezEewFKGRbSR7m", "nexusview");

if ($conn->connect_error) {
  die("Conexão falhou: " . $conn->connect_error);
}

// Verificar se o dispositivo já tem uma playlist vinculada
$sql = "SELECT COUNT(*) as vinculado FROM dispositivos WHERE id = ? AND playlist_id IS NOT NULL";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $dispositivo_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(['vinculado' => $row['vinculado'] > 0]);

$conn->close();
?>
