<?php
$servername = "localhost";
$username = "nexusview";
$password = "AJezEewFKGRbSR7m";
$dbname = "nexusview";
$admin_email = "admin@nexusview.com";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consulta SQL para verificar o usuário
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Verificar se o email é do admin
        if ($email == $admin_email) {
            echo json_encode(["status" => "admin"]);
        } else {
            echo json_encode(["status" => "user"]);
        }
    } else {
        echo json_encode(["status" => "error"]);
    }
}

$conn->close();
?>