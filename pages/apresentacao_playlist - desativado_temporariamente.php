<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Apresentação</title>
    <link rel="stylesheet" href="/styles.css">
    <style>
        .slide {
            display: none;
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            transition: opacity 1s ease-in-out;
            background-color: #000;
            /* Adiciona um fundo preto para melhor visualização */
        }

        .slide.active {
            display: block;
            opacity: 1;
        }

        .caption {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 5px;
            border-radius: 5px;
        }

        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            /* Remove barras de rolagem */
        }

        /* Estilos para a barra de carregamento */
        #loading {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            text-align: center;
            line-height: 100vh;
            font-size: 24px;
            z-index: 1000;
        }
    </style>
</head>

<body>
    <div id="loading">Carregando apresentação...</div>

    <h1>Iniciar Apresentação</h1>
    <form id="form" action="#" method="POST">
        <label for="playlist">Selecione a Playlist:</label><br>
        <select id="playlist" name="playlist" required>
            <!-- As opções serão preenchidas dinamicamente pelo PHP -->
            <?php
            // Inclua o código PHP para preencher playlists
            include '/www/wwwroot/140.238.176.86/API/preencher_playlist.php';
            ?>
        </select><br><br>

        <label for="dispositivo">Selecione o Dispositivo:</label><br>
        <select id="dispositivo" name="dispositivo" required>
            <!-- As opções serão preenchidas dinamicamente pelo PHP -->
            <?php
            // Inclua o código PHP para preencher dispositivos
            include '/www/wwwroot/140.238.176.86/API/preencher_dispositivo.php';
            ?>
        </select><br><br>

        <input type="submit" value="Iniciar Apresentação">
    </form>

    <div id="apresentacao" class="container">
        <!-- Os slides serão adicionados aqui -->
    </div>

    <script>
        let currentSlide = 0;

        function showSlide(slides) {
            slides.forEach((slide) => {
                slide.classList.remove('active');
                slide.style.opacity = '0'; // Remove a visibilidade
            });
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
            slides[currentSlide].style.opacity = '1'; // Torna o slide visível
        }

        function startPresentation(slides) {
            slides[currentSlide].classList.add('active');
            slides[currentSlide].style.opacity = '1'; // Inicialmente visível
            setInterval(() => {
                showSlide(slides);
            }, 5000); // Troca de slide a cada 5 segundos
        }

        // Prevenindo o envio do formulário para que a apresentação comece imediatamente
        document.getElementById('form').addEventListener('submit', function(event) {
            event.preventDefault();
            document.getElementById('loading').style.display = 'block'; // Mostra a barra de carregamento
            const playlistId = document.getElementById('playlist').value;
            const dispositivoId = document.getElementById('dispositivo').value;

            fetch(`http://140.238.176.86/API/iniciar_apresentacao.php?playlist_id=${playlistId}&dispositivo_id=${dispositivoId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loading').style.display = 'none'; // Esconde a barra de carregamento
                    const container = document.getElementById('apresentacao');
                    container.innerHTML = ''; // Limpa o container
                    data.forEach(item => {
                        const slide = document.createElement('div');
                        slide.className = 'slide';
                        let content = '';

                        if (item.tipo.startsWith('image/')) {
                            content = `<img src="http://140.238.176.86/API/visualizar_imagem.php?id=${item.id}" alt="Slide Image">`;
                        } else if (item.tipo.startsWith('video/')) {
                            content = `<video controls autoplay muted><source src="http://140.238.176.86/API/visualizar_video.php?id=${item.id}" type="${item.tipo}"></video>`;
                        } else if (item.tipo === 'text/plain' || item.tipo === 'text/html') {
                            content = `<iframe src="http://140.238.176.86/API/visualizar_texto.php?id=${item.id}"></iframe>`;
                        }

                        const caption = `<div class="caption">${item.nome}</div>`;
                        slide.innerHTML = content + caption;
                        container.appendChild(slide);
                    });

                    const slides = document.querySelectorAll('.slide');
                    startPresentation(slides);
                })
                .catch(error => {
                    console.error('Erro ao iniciar a apresentação:', error);
                    document.getElementById('loading').style.display = 'none'; // Esconde a barra de carregamento em caso de erro
                });
        });
    </script>

</body>

</html>