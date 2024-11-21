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
