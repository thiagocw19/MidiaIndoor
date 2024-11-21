<?php
// Conectar ao banco de dados (substitua com suas credenciais)
$servername = "localhost";
$username = "nexusview";
$password = "AJezEewFKGRbSR7m";
$dbname = "nexusview";


$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verificar se o parametro playlist_id foi passado via GET
if (isset($_GET['playlist_id'])) {
    $playlist_id = $_GET['playlist_id'];
    
    // Consulta SQL para obter os ids dos arquivos associados à playlist
    $sql = "SELECT pa.arquivo_id, a.nome_arquivo, a.tipo_arquivo
            FROM playlist_arquivos pa
            JOIN arquivos a ON pa.arquivo_id = a.id
            WHERE pa.playlist_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $playlist_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $arquivos = [];
    
    // Construir a resposta com os dados dos arquivos
    while ($row = $result->fetch_assoc()) {
        $arquivos[] = [
            'id' => $row['arquivo_id'],
            'nome_arquivo' => $row['nome_arquivo'],
            'tipo_arquivo' => $row['tipo_arquivo'],
        ];
    }

    // Enviar a resposta como JSON
    echo json_encode($arquivos);
} else {
    echo json_encode(['error' => 'playlist_id não fornecido']);
}

$conn->close();
?>
