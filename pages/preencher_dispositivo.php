<?php
// Código para preencher a lista de dispositivos
$servername = "localhost";
$username = "nexusview";
$password = "AJezEewFKGRbSR7m";
$dbname = "nexusview";

// Conectar ao banco de dados
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Buscar dispositivos
    $stmt = $pdo->query("SELECT id, tipo FROM dispositivos");
    $dispositivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Gerar opções HTML
    foreach ($dispositivos as $dispositivo) {
        echo '<option value="' . htmlspecialchars($dispositivo['id']) . '">' . htmlspecialchars($dispositivo['tipo']) . '</option>';
    }

} catch (PDOException $e) {
    echo 'Erro: ' . $e->getMessage();
}
?>
