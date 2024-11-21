document.getElementById('form_login').addEventListener('submit', function (event) {
    event.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    fetch('/API/login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'admin' || data.status === 'user') {
                const username = data.username; // Obtém o nome do usuário

                Swal.fire({
                    title: 'Sucesso!',
                    text: 'Login feito com sucesso!',
                    icon: 'success',
                    toast: true,
                    position: 'top-right',
                    timer: 4000,
                    showConfirmButton: false
                });

                // Redireciona e atualiza o nome de usuário na próxima página
                setTimeout(function () {
                    sessionStorage.setItem('username', username); // Armazena o nome em sessionStorage
                    window.location.href = data.status === 'admin' ? '/pages/menu.html' : '/pages/apresentacao_tv.php';
                }, 1000);
            } else if (data.status === 'user') {
                Swal.fire({
                    title: 'Sucesso!',
                    text: 'Login feito com sucesso!',
                    icon: 'success',
                    toast: true,
                    position: 'top-right',
                    timer: 4000,
                    showConfirmButton: false
                });

                // Adiciona um delay de 4 segundos antes de redirecionar
                setTimeout(function () {
                    window.location.href = '/pages/apresentacao_tv.php';
                }, 1000);
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Email ou Senha incorretos!',
                    icon: 'error',
                    toast: true, // Exibe uma mensagem de sucesso como um toast
                    position: 'top-right', // Posição da mensagem
                    timer: 4000, // Fecha a mensagem após 2 segundos
                    showConfirmButton: false // Não exibe o botão "OK"
                });
            }
        })
        .catch(error => {
            console.error('Erro:', error);
        });
});