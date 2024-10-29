<?php
// Configurações de conexão com o banco de dados
$servername = "localhost";
$username = "nexusview";
$password = "AJezEewFKGRbSR7m";
$dbname = "nexusview";

// Conectar ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Obter parâmetros da URL
$playlist_id = intval($_GET['playlist_id']);
$dispositivo_id = intval($_GET['dispositivo_id']); // Se necessário, pode ser usado em uma condição futura

// Query para selecionar arquivos vinculados à playlist
$sql = "
    SELECT a.id, a.nome_arquivo, a.tipo_arquivo 
    FROM arquivos a 
    JOIN playlist_arquivos pa ON a.id = pa.arquivo_id 
    WHERE pa.playlist_id = ?"; // Nota: `dispositivo_id` não está sendo usado na consulta atual
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $playlist_id);
$stmt->execute();
$result = $stmt->get_result();

// Montar o array de arquivos para retornar em JSON
$arquivos = [];
while ($row = $result->fetch_assoc()) {
    $arquivos[] = [
        'id' => $row['id'],
        'nome' => $row['nome_arquivo'],
        'tipo' => $row['tipo_arquivo']
    ];
}

// Verifique se encontramos arquivos
if (empty($arquivos)) {
    // Se não houver arquivos, retorne uma mensagem apropriada
    echo json_encode(['message' => 'Nenhum arquivo encontrado para esta playlist.']);
} else {
    // Retornar resposta em JSON
    header('Content-Type: application/json');
    echo json_encode($arquivos);
}

// Fechar a conexão
$stmt->close();
$conn->close();
?>
