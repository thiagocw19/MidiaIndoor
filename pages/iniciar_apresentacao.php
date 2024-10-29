<?php
// iniciar_apresentacao.php
header('Content-Type: application/json');

// Simulação de obtenção dos dados da playlist e do dispositivo
$playlist_id = $_GET['playlist_id'];
$dispositivo_id = $_GET['dispositivo_id'];

// Aqui você deve obter os dados do banco de dados.
// Conectar ao banco de dados
$conn = new mysqli("localhost", "nexusview", "AJezEewFKGRbSR7m", "nexusview");

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Selecionar os slides específicos para a playlist e o dispositivo
$sql = "SELECT id, tipo, nome FROM playlist_arquivos WHERE playlist_id = ? AND dispositivo_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $playlist_id, $dispositivo_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Retornar o JSON
echo json_encode($data);

// Fechar a conexão
$stmt->close();
$conn->close();
?>
