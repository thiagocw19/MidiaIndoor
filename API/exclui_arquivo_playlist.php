<?php
// Conectar ao banco de dados
$servername = "localhost";
$username = "nexusview";
$password = "AJezEewFKGRbSR7m";
$dbname = "nexusview";

// Verifique se o ID foi passado via POST
if (isset($_POST['id']) && isset($_POST['playlist_id'])) {
    $id_arquivo = intval($_POST['id']);
    $playlist_id = intval($_POST['playlist_id']); // Obter o ID da playlist

    // Criar conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexão
    if ($conn->connect_error) {
        die('Falha na conexão: ' . $conn->connect_error);
    }

    // Preparar e executar o comando SQL para deletar o vínculo na tabela arquivos_playlist
    $sql = "DELETE FROM playlist_arquivos WHERE arquivo_id = ? AND playlist_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Erro na preparação da consulta: ' . $conn->error);
    }

    $stmt->bind_param('ii', $id_arquivo, $playlist_id);

    if ($stmt->execute()) {
        // Retornar mensagem de sucesso
        echo 'Arquivo desvinculado com sucesso.';
    } else {
        // Retornar mensagem de erro
        echo 'Erro ao desvincular o arquivo: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // Retornar erro se o ID não foi passado
    echo 'ID do arquivo ou ID da playlist não fornecido';
}
?>
