<?php
// Conectar ao banco de dados
$conn = new mysqli("localhost", "nexusview", "AJezEewFKGRbSR7m", "nexusview");

if ($conn->connect_error) {
  die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $dispositivo_id = $_POST['dispositivo_id'];
  $playlist_id = $_POST['playlist_id'];

  // Salvar a associação
  $update_sql = "UPDATE dispositivos SET playlist_id = ? WHERE id = ?";
  $stmt = $conn->prepare($update_sql);
  $stmt->bind_param("ii", $playlist_id, $dispositivo_id);
  $stmt->execute();

  if ($stmt->affected_rows > 0) {
    echo "Associação salva com sucesso.";
  } else {
    echo "Erro ao salvar a associação.";
  }
}

$conn->close();
?>
