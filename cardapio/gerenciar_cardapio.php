<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Cardápio</title>
    <style>
        /* Estilos Gerais */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }

        h1, h2, h3 {
            color: #333;
            text-align: center;
        }

        h1 {
            margin-bottom: 30px;
        }

        /* Formulário */
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto 30px auto;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="number"], textarea, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        button {
            background-color: #28a745;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        /* Itens do Cardápio */
        .item-cardapio {
            background-color: #fff;
            padding: 15px;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .item-cardapio p {
            margin: 0;
        }

        .item-cardapio button {
            background-color: #ffc107;
            margin-left: 10px;
        }

        .item-cardapio button.excluir {
            background-color: #dc3545;
        }

        .item-cardapio button:hover {
            opacity: 0.9;
        }

        /* Promoções */
        .promocao {
            padding: 10px 0;
        }

        .promocao p {
            margin: 10px 0;
            font-size: 16px;
        }

        .promocao .btn-promocao {
            margin-top: 10px;
            padding: 10px 15px;
        }
    </style>
</head>
<body>
    <h1>Gerenciar Cardápio</h1>

    <!-- Formulário para cadastro de itens -->
    <form id="form-item">
        <input type="hidden" id="item_id" name="item_id" value="">
        <label for="nome">Nome do Item (obrigatório):</label>
        <input type="text" id="nome" name="nome" required>

        <label for="descricao">Descrição (opcional):</label>
        <textarea id="descricao" name="descricao"></textarea>

        <label for="preco">Preço (obrigatório):</label>
        <input type="number" step="0.01" id="preco" name="preco" required>

        <button type="submit">Salvar</button>
    </form>

    <h2>Itens do Cardápio</h2>
    <button class="toggle-button" onclick="toggleItens()">Mostrar/Esconder Itens</button>
    <div id="itens_cardapio" style="display: none;">
        <?php
        include 'db_connect.php';
        $result = $conn->query("SELECT * FROM itens_cardapio");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='item-cardapio' id='item_" . $row['id'] . "'>";
                echo "<p>Nome: " . $row['nome'] . " - Preço: R$ " . number_format($row['preco'], 2, ',', '.') . "</p>";
                echo "<div>";
                echo "<button onclick=\"editarItem(" . $row['id'] . ", '" . addslashes($row['nome']) . "', '" . addslashes($row['descricao']) . "', " . $row['preco'] . ")\">Editar</button>";
                echo "<button class='excluir' onclick=\"excluirItem(" . $row['id'] . ")\">Excluir</button>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>Nenhum item cadastrado.</p>";
        }
        ?>
    </div>

    <h2>Promoção do Dia</h2>
    <div class="promocao">
        <form id="form-promocao">
            <label for="item_promocao">Selecione o item:</label>
            <select id="item_promocao" name="item_promocao">
                <?php
                $result = $conn->query("SELECT * FROM itens_cardapio");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                }
                ?>
            </select>

            <label for="valor_promocional">Valor Promocional:</label>
            <input type="number" step="0.01" id="valor_promocional" name="valor_promocional" required>

            <button type="submit">Salvar Promoção</button>
        </form>
    </div>

    <button class="toggle-button" onclick="togglePromocoes()">Mostrar/Esconder Itens em Promoção</button>
    <h3>Itens em Promoção</h3>
    <div id="promocoes" style="display: none;">
        <?php
        $result = $conn->query("
            SELECT ic.id, ic.nome, ic.preco, p.preco_promocional 
            FROM promocoes p 
            JOIN itens_cardapio ic ON p.item_id = ic.id
        ");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<p>" . $row['nome'] . " - Preço Original: R$ " . number_format($row['preco'], 2, ',', '.') . " - Preço Promocional: R$ " . number_format($row['preco_promocional'], 2, ',', '.') . "</p>";
                echo "<button class='btn-promocao excluir' onclick=\"excluirPromocao(" . $row['id'] . ")\">Excluir Promoção</button>";
            }
        } else {
            echo "<p>Nenhuma promoção cadastrada.</p>";
        }
        ?>
    </div>

    <script>
        // Ajax para envio do formulário e exibição da mensagem
        document.getElementById('form-item').addEventListener('submit', function(event) {
            event.preventDefault(); // Previne o envio padrão do formulário

            const formData = new FormData(this); // Cria o objeto FormData com os dados do formulário

            fetch('salvar_item.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data); // Exibe a mensagem recebida do servidor
                location.reload(); // Atualiza a página para mostrar os itens atualizados
            })
            .catch(error => console.error('Erro:', error));
        });

        document.getElementById('form-promocao').addEventListener('submit', function(event) {
            event.preventDefault(); // Previne o envio padrão do formulário

            const formData = new FormData(this); // Cria o objeto FormData com os dados do formulário

            fetch('salvar_promocao.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data); // Exibe a mensagem recebida do servidor
                location.reload(); // Atualiza a página para mostrar as promoções atualizadas
            })
            .catch(error => console.error('Erro:', error));
        });

        // Função para alternar a exibição dos itens
        function toggleItens() {
            const itens = document.getElementById('itens_cardapio');
            itens.style.display = itens.style.display === 'none' ? 'block' : 'none';
        }

        // Função para alternar a exibição dos itens em promoção
        function togglePromocoes() {
            const promocoes = document.getElementById('promocoes');
            promocoes.style.display = promocoes.style.display === 'none' ? 'block' : 'none';
        }

        // Função para editar item
        function editarItem(id, nome, descricao, preco) {
            document.getElementById('item_id').value = id;
            document.getElementById('nome').value = nome;
            document.getElementById('descricao').value = descricao;
            document.getElementById('preco').value = preco;
        }

        // Função para excluir item
        function excluirItem(id) {
            if (confirm('Você tem certeza que deseja excluir este item?')) {
                fetch('excluir_item.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id })
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload(); // Atualiza a página após a exclusão
                })
                .catch(error => console.error('Erro:', error));
            }
        }

        // Função para excluir promoção
        function excluirPromocao(id) {
            if (confirm('Você tem certeza que deseja excluir esta promoção?')) {
                fetch('excluir_promocao.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id })
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload(); // Atualiza a página após a exclusão
                })
                .catch(error => console.error('Erro:', error));
            }
        }
    </script>
</body>
</html>
