<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['item_id']) ? $_POST['item_id'] : null;
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];

    if ($id) {
        // Editar item
        $stmt = $conn->prepare("UPDATE itens_cardapio SET nome=?, descricao=?, preco=? WHERE id=?");
        $stmt->bind_param("ssdi", $nome, $descricao, $preco, $id);
        $stmt->execute();
        echo "Item editado com sucesso!";
    } else {
        // Adicionar item
        $stmt = $conn->prepare("INSERT INTO itens_cardapio (nome, descricao, preco) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $nome, $descricao, $preco);
        $stmt->execute();
        echo "Item adicionado com sucesso!";
    }
}
?>
