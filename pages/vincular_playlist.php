<?php
// Conectar ao banco de dados
$conn = new mysqli("localhost", "nexusview", "AJezEewFKGRbSR7m", "nexusview");

if ($conn->connect_error) {
  die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $dispositivo_id = $_POST['dispositivo'];
  $playlist_id = $_POST['playlist'];

  // Atualizar o dispositivo com a playlist escolhida
  $sql = "UPDATE dispositivos SET playlist_id = ? WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $playlist_id, $dispositivo_id);

  if ($stmt->execute()) {
    echo "Associação salva com sucesso!";
  } else {
    echo "Erro ao salvar associação: " . $stmt->error;
  }

  $stmt->close();
}

$conn->close();
header('Location: gerenciar_playlists_dispositivos.php');
