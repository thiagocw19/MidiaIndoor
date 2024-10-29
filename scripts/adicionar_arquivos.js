function toggleInputs() {
    const uploadType = document.getElementById('upload-type').value;
    const fileInput = document.getElementById('file-input');
    const textInput = document.getElementById('text-input');

    if (uploadType === 'text') {
        fileInput.style.display = 'none';
        textInput.style.display = 'block';
        CKEDITOR.replace('editor1');
    } else {
        fileInput.style.display = 'block';
        textInput.style.display = 'none';
        if (CKEDITOR.instances.editor1) {
            CKEDITOR.instances.editor1.destroy();
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    toggleInputs();
});

document.getElementById('upload-form').addEventListener('submit', function (event) {
    event.preventDefault(); // Impede o envio padrão do formulário

    var formData = new FormData(this);
    var loadingElement = document.getElementById('loading');
    var loadingBarFill = document.getElementById('loading-bar-fill');
    var successMessage = document.getElementById('success-message');

    loadingElement.style.display = 'block';

    fetch('http://140.238.176.86/API/upload_arquivo.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())
        .then(text => {
            loadingBarFill.style.width = '100%';
            setTimeout(() => {
                loadingElement.style.display = 'none';
                successMessage.innerText = text;
                successMessage.style.display = 'block';
                setTimeout(() => {
                    location.reload(); // Recarrega a página após 2 segundos
                }, 2000);
            }, 400); // Duração da animação da barra de carregamento
        })
        .catch(error => {
            loadingBarFill.style.width = '100%';
            setTimeout(() => {
                loadingElement.style.display = 'none';
                successMessage.innerText = 'Arquivo Enviado com Sucesso!!.';
                successMessage.style.display = 'block';
                setTimeout(() => {
                    location.reload(); // Recarrega a página após 2 segundos
                }, 2000);
            }, 400); // Duração da animação da barra de carregamento
        });
});