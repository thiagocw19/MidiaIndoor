<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cardápio</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            background-image: url('fundo-cardapio.jpg');
            background-size: cover;
        }

        h1 {
            font-size: 3rem;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        #itens_cardapio {
            width: 80%;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
        }

        .item-cardapio {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ccc;
        }

        .item-cardapio p {
            margin: 0;
        }

        .item-cardapio strong {
            font-size: 1.5rem;
            font-weight: bold;
            color: #444;
        }

        .item-descricao {
            font-size: 1rem;
            color: #777;
            margin-top: 5px;
        }

        .item-preco {
            font-size: 1.5rem;
            color: #444;
            text-align: right;
        }

        .item-cardapio.promocao {
            text-decoration: line-through; /* Riscar o preço do item no cardápio */
        }

        #promocao_dia {
            width: 80%;
            background-color: #ffd700;
            padding: 15px;
            border-radius: 10px;
            margin-top: 30px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            animation: destaque 1s ease-in-out infinite alternate;
        }

        @keyframes destaque {
            from {
                box-shadow: 0px 0px 20px rgba(255, 0, 0, 0.3);
            }
            to {
                box-shadow: 0px 0px 30px rgba(255, 0, 0, 0.7);
            }
        }

        .promocao-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 1.5rem; /* Aumentar a fonte dos itens em promoção */
        }

        .promocao-item s {
            color: red;
            margin-right: 10px;
        }

        .promocao-preco {
            font-size: 1.5rem;
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Cardápio</h1>

    <div id="itens_cardapio">
        <?php
        include 'db_connect.php';
        $result = $conn->query("SELECT * FROM itens_cardapio");
        $promocao_ids = []; // Array para armazenar os IDs dos itens em promoção

        // Obtém os IDs dos itens em promoção
        $result_promocao = $conn->query("SELECT item_id FROM promocoes");
        while ($promo_row = $result_promocao->fetch_assoc()) {
            $promocao_ids[] = $promo_row['item_id'];
        }

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $is_promocao = in_array($row['id'], $promocao_ids); // Verifica se o item está em promoção
                echo "<div class='item-cardapio" . ($is_promocao ? " promocao" : "") . "'>";
                echo "<div>";
                echo "<p><strong>" . $row['nome'] . "</strong></p>";
                if (!empty($row['descricao'])) {
                    echo "<p class='item-descricao'>" . $row['descricao'] . "</p>";
                }
                echo "</div>";
                echo "<p class='item-preco'>R$ " . $row['preco'] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>Nenhum item cadastrado.</p>";
        }
        ?>
    </div>

    <div id="promocao_dia">
        <h2>Promoção do Dia</h2>
        <?php
        $result = $conn->query("
            SELECT ic.nome, ic.preco, p.preco_promocional 
            FROM promocoes p 
            JOIN itens_cardapio ic ON p.item_id = ic.id
        ");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='promocao-item'>";
                echo "<p><strong>" . $row['nome'] . "</strong> - Preço Original: <s>R$" . $row['preco'] . "</s></p>";
                echo "<p class='promocao-preco'>R$" . $row['preco_promocional'] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>Nenhum item em promoção.</p>";
        }
        ?>
    </div>

    <script>
        // Atualiza automaticamente o cardápio a cada 2 segundos
        setInterval(function() {
            location.reload();
        }, 2000);
    </script>
</body>
</html>
