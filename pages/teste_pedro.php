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
            flex-direction: column;
            align-items: center;
            width: 80%;
            flex-grow: 1;
            overflow: hidden;
        }

        #promocao_dia {
            margin: 1em;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 10px;
            border-radius: 30px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            text-align: center;
        }

        #itens_cardapio {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 10px;
            border-radius: 30px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        #itens {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin: 1em;
        }
        #promocoes{
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin: 1em;
        }

        .section-cardapio {
            margin-bottom: 20px;
        }

        .item-cardapio, .promocao-item {
            width: 45%;
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

        /* Nova regra para o <s> */
        s {
            color: red;  /* Define a cor vermelha para o texto riscado */
        }
        
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
            margin: 20px auto; /* Centraliza o loader */
        }

        .loading-message {
            width: 100vw;
            margin: auto;
        }
        
        .div_promo {
            padding: .3rem;
            background-color: yellow;
            border-bottom: 3px solid black;
            border-radius: 10px;
        }
        
        .div_geral {
            border-radius: 30px;
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
        
        
        
        
    </style>
</head>
<body>
    <div id="container">
        <div id="promocao_dia">
            <h2>Promoção do Dia</h2>
            <div id="promocoes"></div>
            <div class="loader" id="loader_promocao"></div>
            
        </div>

        <div id="itens_cardapio">
            <div class="section-cardapio">
                <h2 style="text-align:center">Itens do Cardápio</h2>
               
                <div id="itens"></div>
            </div>
        </div>
    </div>

    <script>
        // Função para carregar os itens de cardápio
        function carregarCardapio() {
            fetch('get_cardapio.php')  // Usando o mesmo arquivo PHP para ambos os blocos
                .then(response => response.text())
                .then(data => {
                    // Carregar os itens no "Itens do Cardápio"
                    document.getElementById('itens').innerHTML = data;
                    document.getElementById('loader_cardapio').style.display = 'none';

                    // Carregar os itens também na "Itens do Cardápio 2"
                    document.getElementById('conteudo_outra_area').innerHTML = data;
                    document.getElementById('loader_outra_area').style.display = 'none';
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
                    document.getElementById('loader_promocao').style.display = 'none';
                })
                .catch(error => {
                    console.error("Erro ao carregar promoções:", error);
                });
        }

        // Carregar os dados ao carregar a página
        window.onload = function() {
            carregarCardapio();  // Chama a função que carrega os itens de cardápio
            carregarPromocoes();  // Chama a função que carrega as promoções

            // Atualiza os itens e promoções a cada 3 segundos
            setInterval(() => {
                carregarCardapio();
                carregarPromocoes();
            }, 3000);
        };
    </script>
</body>
</html>
