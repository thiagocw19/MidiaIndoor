<?php
include 'db_connect.php'; // Conexão com o banco de dados

// Busca os itens do cardápio
$result = $conn->query("SELECT * FROM itens_cardapio");
$promocao_ids = [];

// Verifica promoções para saber se um item está em promoção
$result_promocao = $conn->query("SELECT item_id FROM promocoes");
while ($promo_row = $result_promocao->fetch_assoc()) {
    $promocao_ids[] = $promo_row['item_id'];
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $is_promocao = in_array($row['id'], $promocao_ids);
        
        // Início da renderização do item
        echo "<div class='item-cardapio" . ($is_promocao ? " promocao" : "") . "'>";
        echo "<div>";
        
        // Se o item estiver em promoção, o nome do item será envolvido em <s>...</s>
        if ($is_promocao) {
            echo "<p><strong><s class='riscado'>" . $row['nome'] . "</s></strong></p>";
        } else {
            echo "<p><strong>" . $row['nome'] . "</strong></p>";
        }

        // Se existir descrição, exibe a descrição
        if (!empty($row['descricao'])) {
            echo "<p class='item-descricao'>" . $row['descricao'] . "</p>";
        }
        
        echo "</div>";
        
        // Exibe o preço normalmente
        echo "<p class='item-preco'>R$ " . $row['preco'] . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>Nenhum item encontrado.</p>";
}
?>
