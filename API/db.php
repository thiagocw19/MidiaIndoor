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


} catch (PDOException $e) {
    echo 'Erro: ' . $e->getMessage();
}
?>