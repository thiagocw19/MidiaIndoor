<?php
$dispositivo_id = $_GET['dispositivo_id'];

// Conectar ao banco de dados
$conn = new mysqli("localhost", "nexusview", "AJezEewFKGRbSR7m", "nexusview");

if ($conn->connect_error) {
  die("Conexão falhou: " . $conn->connect_error);
}

// Selecionar o dispositivo e sua playlist
$sql = "SELECT dispositivos.id, dispositivos.tipo, dispositivos.modelo, dispositivos.playlist_id
        FROM dispositivos
        WHERE dispositivos.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $dispositivo_id);
$stmt->execute();
$result = $stmt->get_result();
$dispositivo = $result->fetch_assoc();

// Selecionar todas as playlists para edição
$sql = "SELECT id, nome FROM playlists";
$playlists = $conn->query($sql);
?>

<form method="POST" action="salvar_edicao.php">
  <input type="hidden" name="dispositivo_id" value="<?= $dispositivo_id ?>">
  
  <label for="playlists">Selecionar nova playlist:</label>
  <select name="playlists" id="playlists">
    <?php while ($playlist = $playlists->fetch_assoc()): ?>
      <option value="<?= $playlist['id'] ?>" <?= $dispositivo['playlist_id'] == $playlist['id'] ? 'selected' : '' ?>>
        <?= $playlist['nome'] ?>
      </option>
    <?php endwhile; ?>
  </select>

  <button type="submit">Salvar</button>
</form>

<?php
// Lógica para processar o salvamento da edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $dispositivo_id = $_POST['dispositivo_id'];
  $playlist_id = $_POST['playlists'];

  // Atualizar a associação
  $update_sql = "UPDATE dispositivos SET playlist_id = ? WHERE id = ?";
  $stmt = $conn->prepare($update_sql);
  $stmt->bind_param("ii", $playlist_id, $dispositivo_id);
  $stmt->execute();

  if ($stmt->affected_rows > 0) {
    echo "<p>Associação atualizada com sucesso!</p>";
  } else {
    echo "<p>Erro ao atualizar a associação.</p>";
  }
}

$conn->close();
?>
