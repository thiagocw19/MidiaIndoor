<?php
$servername = "localhost";
$username = "nexusview";
$password = "AJezEewFKGRbSR7m";
$dbname = "nexusview";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Selecionar playlists
$sql = "SELECT * FROM playlists";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $playlists = array();
    while ($row = $result->fetch_assoc()) {
        $playlists[] = array(
            'id' => $row["id"],
            'nome' => $row["nome"],
            'descricao' => $row["descricao"]
        );
    }
    echo json_encode($playlists);
} else {
    echo json_encode(array());
}

$conn->close();
?>