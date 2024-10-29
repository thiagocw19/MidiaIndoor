<?php
// Conectar ao banco de dados
$servername = "localhost";
$username = "nexusview";
$password = "AJezEewFKGRbSR7m";
$dbname = "nexusview";

// Verifique se o ID foi passado via POST
if (isset($_POST['id'])) {
    $id_arquivo = intval($_POST['id']);

    // Criar conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexão
    if ($conn->connect_error) {
        die(json_encode(['status' => 'error', 'message' => 'Falha na conexão: ' . $conn->connect_error]));
    }

    // Preparar e executar o comando SQL para deletar o arquivo
    $sql = "DELETE FROM arquivos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_arquivo);

    if ($stmt->execute()) {
        // Retornar sucesso
        echo json_encode(['status' => 'success', 'message' => 'Arquivo excluído com sucesso']);
    } else {
        // Retornar erro
        echo json_encode(['status' => 'error', 'message' => 'Erro ao excluir o arquivo: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    // Retornar erro se o ID não foi passado
    echo json_encode(['status' => 'error', 'message' => 'ID do arquivo não fornecido']);
}
