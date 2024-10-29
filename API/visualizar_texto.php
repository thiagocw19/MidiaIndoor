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

// Verificar se o ID do arquivo foi passado
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Preparar e executar a consulta
    $stmt = $conn->prepare("SELECT tipo_arquivo, conteudo FROM arquivos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($tipo_arquivo, $conteudo);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();

        // Para arquivos de texto (.txt) e HTML
        if ($tipo_arquivo === 'text/plain' || $tipo_arquivo === 'text/html') {
            // Enviar cabeçalhos apropriados
            header("Content-Type: $tipo_arquivo");
            echo $conteudo;
        } else {
            echo "Tipo de arquivo não suportado.";
        }
    } else {
        echo "Arquivo não encontrado.";
    }

    $stmt->close();
} else {
    echo "ID do arquivo não fornecido.";
}

// Fechar a conexão
$conn->close();
?>
