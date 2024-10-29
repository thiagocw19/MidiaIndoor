<?php
// Configurações do banco de dados
$host = 'localhost'; // Ou o IP do servidor do banco de dados
$dbname = 'nexusview';
$username = 'nexusview';
$password = 'AJezEewFKGRbSR7m';

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
