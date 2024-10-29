document.getElementById('form_users').addEventListener('submit', function (event) {
    event.preventDefault(); // Impede o envio padrão do formulário

    const formData = new FormData(this);

    fetch('/API/user.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.success) {
                Swal.fire({
                    title: 'Sucesso!',
                    text: 'Corno cadastrado com sucesso!',
                    icon: 'success',
                    toast: true, // Exibe uma mensagem de sucesso como um toast
                    position: 'top-right', // Posição da mensagem
                    timer: 4000, // Fecha a mensagem após 2 segundos
                    showConfirmButton: false // Não exibe o botão "OK"
                });
                this.reset();
            } else {
                Swal.fire({
                    title: 'F!',
                    text: 'Erro ao cadastrar o corno!',
                    icon: 'error',
                    toast: true, // Exibe uma mensagem de sucesso como um toast
                    position: 'top-right', // Posição da mensagem
                    timer: 4000, // Fecha a mensagem após 2 segundos
                    showConfirmButton: false // Não exibe o botão "OK"
                });
                this.reset(); // Limpa o formulário
            }
        })
        .catch(error => console.error('Erro ao cadastrar o corno do usuario:', error));
});