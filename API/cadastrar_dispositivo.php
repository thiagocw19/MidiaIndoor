<?php

// Dados de acesso ao banco de dados
$servername = "localhost";
$username = "nexusview";
$password = "AJezEewFKGRbSR7m";
$dbname = "nexusview";
header("Access-Control-Allow-Origin: *");
// Realiza a conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// adiciona os dados no cadastrados no banco de dados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo = $_POST['tipo'];
    $modelo = $_POST['modelo'];
    $resolucao = $_POST['resolucao'];

    $stmt = $conn->prepare("INSERT INTO dispositivos (tipo, modelo, resolucao) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $tipo, $modelo, $resolucao);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Dispositivo criado com sucesso!']);
    } else {
        echo json_encode(['message' => 'Erro ao criar o Dispositivo.']);
    }

    $stmt->close();
}

// Fecha a conexão do banco de dados
$conn->close();
