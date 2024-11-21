<?php
include 'db_connect.php';

$result = $conn->query("SELECT ic.nome, ic.preco, p.preco_promocional FROM promocoes p JOIN itens_cardapio ic ON p.item_id = ic.id");
$output = '';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= "<div class='promocao-item div_promo'>";
        $output .= "<p><strong>" . $row['nome'] . "</strong> - Preço Original: <s>R$ " . $row['preco'] . "</s></p>";
        $output .= "<p class='promocao-preco'>R$ " . $row['preco_promocional'] . "</p>";
        $output .= "</div>";
    }
} else {
    $output .= "<div class='loading-message'>Trabalhando em novas promoções...</div><div class='loader'></div>";
}

echo $output;
?>
