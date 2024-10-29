<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $id = $data->id;

    $stmt = $conn->prepare("DELETE FROM promocoes WHERE item_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "Promoção excluída com sucesso!";
}
?>
