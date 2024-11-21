<?php
// Verifique se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Configuração do banco de dados
    $servername = "localhost";
    $username = "nexusview";
    $password = "AJezEewFKGRbSR7m";
    $dbname = "nexusview";

    // Criar a conexão com o banco de dados
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar se a conexão falhou
    if ($conn->connect_error) {
        echo json_encode(['status' => 'error', 'message' => 'Erro na conexão com o banco de dados: ' . $conn->connect_error]);
        exit;
    }

    // Receber os dados do POST
    $playlistId = intval($_POST['playlist_id']);
    $arquivoIds = json_decode($_POST['arquivo_ids'], true); // Decodificar o array de IDs dos arquivos

    // Verificar se os dados são válidos
    if (empty($playlistId) || empty($arquivoIds)) {
        echo json_encode(['status' => 'error', 'message' => 'Dados inválidos ou incompletos.']);
        exit;
    }

    // Preparar a inserção dos arquivos na playlist
    $success = true;
    foreach ($arquivoIds as $arquivoId) {
        $arquivoId = intval($arquivoId);
        
        // Verificar se o arquivo já não está na playlist
        $checkSql = "SELECT * FROM playlist_arquivos WHERE playlist_id = ? AND arquivo_id = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("ii", $playlistId, $arquivoId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        
        // Se o arquivo já estiver na playlist, pular a inserção
        if ($checkResult->num_rows > 0) {
            continue;
        }

        // Inserir o arquivo na playlist
        $sql = "INSERT INTO playlist_arquivos (playlist_id, arquivo_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $playlistId, $arquivoId);

        if (!$stmt->execute()) {
            $success = false;
            break; // Se falhar, interromper o loop
        }
    }

    // Fechar a conexão
    $conn->close();

    // Retornar a resposta em JSON
    if ($success) {
        echo json_encode(['status' => 'success', 'message' => 'Arquivos adicionados com sucesso à playlist.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao adicionar arquivos à playlist.']);
    }
} else {
    // Caso não seja uma requisição POST
    echo json_encode(['status' => 'error', 'message' => 'Requisição inválida.']);
}
?>