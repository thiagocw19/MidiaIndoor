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
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];

    $stmt = $conn->prepare("INSERT INTO playlists (nome, descricao) VALUES (?, ?)");
    $stmt->bind_param("ss", $nome, $descricao);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Playlist criada com sucesso!']);
    } else {
        echo json_encode(['message' => 'Erro ao criar a playlist.']);
    }

    $stmt->close();
}

// Fecha a conexão do banco de dados
$conn->close();
