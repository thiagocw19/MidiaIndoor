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
$sql = "SELECT * FROM dispositivos";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $dispositivos = array();
    while ($row = $result->fetch_assoc()) {
        $dispositivos[] = array(
            'id' => $row["id"],
            'modelo' => $row["modelo"],
            'resolucao' => $row["resolucao"]
        );
    }
    echo json_encode($dispositivos);
} else {
    echo json_encode(array());
}

$conn->close();
?>