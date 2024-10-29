<?php
// Código para preencher a lista de playlists
$host = "localhost"; // Defina o valor correto do host
$username = "nexusview";
$password = "AJezEewFKGRbSR7m";
$dbname = "nexusview";

// Conectar ao banco de dados
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Buscar playlists
    $stmt = $pdo->query("SELECT id, nome FROM playlists");
    $playlists = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Gerar opções HTML
    foreach ($playlists as $playlist) {
        echo '<option value="' . htmlspecialchars($playlist['id']) . '">' . htmlspecialchars($playlist['nome']) . '</option>';
    }

} catch (PDOException $e) {
    echo 'Erro: ' . $e->getMessage();
}
?>
