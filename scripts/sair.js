function sair() {
    setTimeout(function () {
        window.location.href = '/index.html';
    }, 1000);
    Swal.fire({
        title: 'Sucesso!',
        text: 'Até Logo!',
        icon: 'success',
        toast: true,
        position: 'top-right',
        timer: 4000,
        showConfirmButton: false
    });
}