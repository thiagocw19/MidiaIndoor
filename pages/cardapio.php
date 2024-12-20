<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cardápio</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow: hidden;
            background-image: url('/images/cardapio.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: 'Courier New', monospace;
        }

        h1 {
            font-size: 2.5rem;
            color: white;
            margin: 20px 0;
            text-align: center;
        }

        #container {
            display: flex;
            width: 80%;
            justify-content: space-between;
            flex-grow: 1;
            overflow: hidden;
            position: relative;
        }

        #itens_cardapio, #promocao_dia {
            width: 48%;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .riscado{
            text-decoration: line-through;    /* Garante que o texto fica riscado */
            text-decoration-color: red;       /* Altera a cor do traço para vermelho */
            text-decoration-thickness: 2px;   /* Define a espessura do traço, se desejar */
        }

        .item-cardapio, .promocao-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #ccc;
        }

        .item-cardapio p, .promocao-item p {
            margin: 0;
        }

        .item-cardapio strong, .promocao-item strong {
            font-size: 1.4rem;
            font-weight: bold;
            color: #444;
        }

        .item-descricao {
            font-size: 1rem;
            color: #777;
            margin-top: 5px;
        }

        .item-preco, .promocao-preco {
            font-size: 1.4rem;
            color: green;
            font-weight: bold;
            text-align: right;
        }

        .item-cardapio.promocao {
            text-decoration: line-through red;
            color: #ccc;
        }

        #promocao_dia {
            margin-left: 20px;
        }

        .promocao-item {
            animation: highlight 1s ease infinite alternate;
        }

        @keyframes highlight {
            0% { background-color: rgba(255, 215, 0, 0.5); }
            100% { background-color: rgba(255, 215, 0, 1); }
        }

        /* Ajuste do estilo do loader */
        .loader {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 8px solid #d1914b;
            box-sizing: border-box;
            --c: no-repeat radial-gradient(farthest-side, #d64123 94%,#0000);
            --b: no-repeat radial-gradient(farthest-side, #000 94%,#0000);
            background:
                var(--c) 11px 15px,
                var(--b) 6px 15px,
                var(--c) 35px 23px,
                var(--b) 29px 15px,
                var(--c) 11px 46px,
                var(--b) 11px 34px,
                var(--c) 36px 0px,
                var(--b) 50px 31px,
                var(--c) 47px 43px,
                var(--b) 31px 48px,
                #f6d353;
            background-size: 15px 15px, 6px 6px;
            animation: l4 3s infinite;
            margin-top: 20px; /* Adiciona um espaçamento antes do loader */
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        @keyframes l4 {
            0%     { -webkit-mask: conic-gradient(#0000 0, #000 0) }
            16.67% { -webkit-mask: conic-gradient(#0000 60deg, #000 0) }
            33.33% { -webkit-mask: conic-gradient(#0000 120deg,#000 0) }
            50%    { -webkit-mask: conic-gradient(#0000 180deg,#000 0) }
            66.67% { -webkit-mask: conic-gradient(#0000 240deg,#000 0) }
            83.33% { -webkit-mask: conic-gradient(#0000 300deg,#000 0) }
            100%   { -webkit-mask: conic-gradient(#0000 360deg,#000 0) }
        }

        .loading-message {
            text-align: center;
            color: black;
            font-size: 1.5rem;
            animation: fade 3s infinite;
        }

        @keyframes fade {
            0%, 100% { opacity: 0; }
            50% { opacity: 1; }
        }
       
    </style>
</head>
<body>
    <h1>Cardápio</h1>
    <div id="container">
        <div id="itens_cardapio">
            <h2 style="text-align:center">Itens do Cardápio</h2>
            <div class="loader" id="loader_cardapio"></div>
            <div id="itens"></div>
        </div>

        <div id="promocao_dia">
            <h2 style="text-align:center">Promoção do Dia</h2>
            <div id="promocoes"></div>
            <!-- O loader para a promoção ficará aqui abaixo -->
            <div class="loader" id="loader_promocao"></div>
        </div>
    </div>

    <script>
        // Função para carregar os itens do cardápio
        function carregarCardapio() {
            fetch('get_cardapio.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('itens').innerHTML = data;
                    document.getElementById('loader_cardapio').style.display = 'none'; // Esconde o loader
                })
                .catch(error => {
                    console.error("Erro ao carregar cardápio:", error);
                });
        }

        // Função para carregar as promoções
        function carregarPromocoes() {
            fetch('get_promocoes.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('promocoes').innerHTML = data;
                    document.getElementById('loader_promocao').style.display = 'none'; // Esconde o loader
                })
                .catch(error => {
                    console.error("Erro ao carregar promoções:", error);
                });
        }

        // Carregar os dados ao carregar a página
        window.onload = function() {
            carregarCardapio();
            carregarPromocoes();
            
            // Atualizar os itens e promoções a cada 3 segundos
            setInterval(() => {
                carregarCardapio();
                carregarPromocoes();
            }, 3000);
        };
    </script>
</body>
</html>
