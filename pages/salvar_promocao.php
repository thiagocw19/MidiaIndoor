<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_promocao'];
    $valor_promocional = $_POST['valor_promocional'];

    $stmt = $conn->prepare("INSERT INTO promocoes (item_id, preco_promocional) VALUES (?, ?)");
    $stmt->bind_param("id", $item_id, $valor_promocional);
    $stmt->execute();
    echo "Promoção cadastrada com sucesso!";
}
?>
