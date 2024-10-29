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

        // Definir cabeçalhos de tipo de conteúdo e aceitar intervalos
        header("Content-Type: $tipo_arquivo");
        header("Accept-Ranges: bytes");

        $fileSize = strlen($conteudo);
        $start = 0;
        $end = $fileSize - 1;

        // Verificar se há uma solicitação de intervalo
        if (isset($_SERVER['HTTP_RANGE'])) {
            $range = $_SERVER['HTTP_RANGE'];
            list(, $range) = explode('=', $range, 2);
            list($start, $end) = explode('-', $range);

            // Conversão para inteiros
            $start = intval($start);
            if ($end === '') {
                $end = $fileSize - 1;
            } else {
                $end = intval($end);
            }

            // Definir cabeçalhos de resposta para partial content
            header("HTTP/1.1 206 Partial Content");
            header("Content-Range: bytes $start-$end/$fileSize");
            header("Content-Length: " . ($end - $start + 1));
        } else {
            // Cabeçalho de comprimento total do conteúdo
            header("Content-Length: $fileSize");
        }

        // Enviar o conteúdo em partes usando fread
        $chunkSize = 8192; // 8KB por bloco
        $bytesSent = 0;

        while ($start + $bytesSent <= $end) {
            echo substr($conteudo, $start + $bytesSent, $chunkSize);
            $bytesSent += $chunkSize;
            ob_flush();
            flush();
        }
        exit;
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
