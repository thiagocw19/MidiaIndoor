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

    // Preparar e executar o comando SQL para deletar os registros relacionados na tabela playlist_arquivos
    $sql_delete_playlist = "DELETE FROM playlist_arquivos WHERE arquivo_id = ?";
    $stmt_playlist = $conn->prepare($sql_delete_playlist);
    $stmt_playlist->bind_param('i', $id_arquivo);
    
    if (!$stmt_playlist->execute()) {
        // Retornar erro se falhar ao excluir registros relacionados
        echo json_encode(['status' => 'error', 'message' => 'Erro ao excluir registros relacionados: ' . $stmt_playlist->error]);
        $stmt_playlist->close();
        $conn->close();
        exit;
    }
    
    $stmt_playlist->close();

    // Agora, preparar e executar o comando SQL para deletar o arquivo
    $sql_delete_arquivo = "DELETE FROM arquivos WHERE id = ?";
    $stmt_arquivo = $conn->prepare($sql_delete_arquivo);
    $stmt_arquivo->bind_param('i', $id_arquivo);

    if ($stmt_arquivo->execute()) {
        // Retornar sucesso
        echo json_encode(['status' => 'success', 'message' => 'Arquivo excluído com sucesso']);
    } else {
        // Retornar erro
        echo json_encode(['status' => 'error', 'message' => 'Erro ao excluir o arquivo: ' . $stmt_arquivo->error]);
    }

    $stmt_arquivo->close();
    $conn->close();
} else {
    // Retornar erro se o ID não foi passado
    echo json_encode(['status' => 'error', 'message' => 'ID do arquivo não fornecido']);
}
?>