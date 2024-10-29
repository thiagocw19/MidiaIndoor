<?php
$servername = "localhost";
$username = "nexusview";
$password = "AJezEewFKGRbSR7m";
$dbname = "nexusview";

// Conectar ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Função para gerar um nome de arquivo único
function gerarNomeArquivo($extensao) {
    return uniqid('arquivo_', true) . '.' . $extensao;
}

// Verificar o tipo de conteúdo enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $upload_type = $_POST['upload_type'];

    if ($upload_type === 'text') {
        // Armazenar texto do CKEditor
        if (!empty($_POST['texto'])) {
            $texto = $_POST['texto'];
            $nome_arquivo = gerarNomeArquivo('html'); // Gerar um nome único com extensão HTML

            // Inserir texto no banco de dados
            $stmt = $conn->prepare("INSERT INTO arquivos (nome_arquivo, texto, data_upload) VALUES (?, ?, NOW())");
            if ($stmt) {
                $stmt->bind_param("ss", $nome_arquivo, $texto);
                $stmt->execute();
                echo "Texto salvo com sucesso!";
            } else {
                echo "Erro na preparação da declaração: " . $conn->error;
            }
        } else {
            echo "Nenhum texto enviado.";
        }

    } elseif ($upload_type === 'file') {
        // Verificar se o arquivo foi enviado corretamente
        if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['arquivo'];
            $nome_arquivo = $file['name'];
            $tipo_arquivo = $file['type'];
            $tamanho = $file['size'];

            // Verificar tipos de arquivo permitidos
            $tipos_permitidos = ['video/mp4', 'video/x-ms-wmv', 'image/jpeg', 'image/png', 'image/gif'];

            if (!in_array($tipo_arquivo, $tipos_permitidos)) {
                echo "Tipo de arquivo não permitido. Recebido: " . $tipo_arquivo;
                exit;
            }

            // Ler o conteúdo do arquivo
            $conteudo = file_get_contents($file['tmp_name']);

            // Inserir arquivo no banco de dados
            $stmt = $conn->prepare("INSERT INTO arquivos (nome_arquivo, tipo_arquivo, tamanho, conteudo, data_upload) VALUES (?, ?, ?, ?, NOW())");

            if ($stmt) {
                $stmt->bind_param("ssis", $nome_arquivo, $tipo_arquivo, $tamanho, $conteudo);
                $stmt->send_long_data(3, $conteudo); // Enviar o conteúdo binário
                $stmt->execute();
                echo "Arquivo salvo com sucesso!";
            } else {
                echo "Erro na preparação da declaração: " . $conn->error;
            }
        } else {
            echo "Nenhum arquivo enviado ou ocorreu um erro: " . $_FILES['arquivo']['error'];
        }
    } else {
        echo "Tipo de upload inválido.";
    }
}

$conn->close();
?>
