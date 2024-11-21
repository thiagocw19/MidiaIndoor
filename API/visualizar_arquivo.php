<?php
// Conectar ao banco de dados (substitua com suas credenciais)
$servername = "localhost";
$username = "nexusview";
$password = "AJezEewFKGRbSR7m";
$dbname = "nexusview";  // Substitua pelo nome do seu banco

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verificar se o parametro id foi passado via GET
if (isset($_GET['id'])) {
    $arquivo_id = $_GET['id'];
    
    // Consulta SQL para obter os dados do arquivo com base no id
    $sql = "SELECT nome_arquivo, tipo_arquivo, conteudo, texto
            FROM arquivos
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $arquivo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Verificar se o arquivo foi encontrado
    if ($row = $result->fetch_assoc()) {
        $nome_arquivo = $row['nome_arquivo'];
        $tipo_arquivo = $row['tipo_arquivo'];
        $conteudo = $row['conteudo'];
        $texto = $row['texto'];
        
        // Definir o cabeçalho de resposta baseado no tipo do arquivo
        if (strpos($tipo_arquivo, 'image') !== false) {
            // Arquivo de imagem
            header("Content-Type: " . $tipo_arquivo);
            echo $conteudo;
        } elseif (strpos($tipo_arquivo, 'video') !== false) {
            // Arquivo de vídeo
            header("Content-Type: " . $tipo_arquivo);
            echo $conteudo;
        } elseif (strpos($tipo_arquivo, 'text') !== false) {
            // Arquivo de texto
            header("Content-Type: text/plain; charset=utf-8");
            echo $texto;
        } else {
            // Tipo de arquivo desconhecido
            header("HTTP/1.1 415 Unsupported Media Type");
            echo "Tipo de arquivo não suportado.";
        }
        } else {
            // Arquivo não encontrado
            header("HTTP/1.1 404 Not Found");
            echo "Arquivo não encontrado.";
            }
        } else {
        // Parâmetro 'id' não foi passado
        header("HTTP/1.1 400 Bad Request");
        echo "ID do arquivo não fornecido.";
    }

$conn->close();
?>
