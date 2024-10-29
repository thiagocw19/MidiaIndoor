<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = $_POST['item_promocao'];
    $valorPromocional = $_POST['valor_promocional'];

    $stmt = $conn->prepare("INSERT INTO promocoes (item_id, preco_promocional) VALUES (?, ?)");
    $stmt->bind_param("id", $itemId, $valorPromocional);

    if ($stmt->execute()) {
        echo "Promoção salva com sucesso.";
    } else {
        echo "Erro ao salvar promoção: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: gerenciar_cardapio.php"); // Redireciona de volta para a página
    exit();
}
?>
