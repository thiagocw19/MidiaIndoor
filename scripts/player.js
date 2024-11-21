class Arquivo {
    constructor(link, tipo) {
        this.link = link; // URL do arquivo
        this.tipo = tipo; // 1: imagem, 2: vídeo, 3: texto
        this.dados;
        this.erro;
    }
}

var lstArquivos = [];

const CACHE_NAME = 'ArquivosPlayList';

async function baixarArquivo(arq) {
    const cache = await caches.open(CACHE_NAME);

    // Tenta buscar do cache primeiro
    const cachedResponse = await cache.match(arq.link);
    if (cachedResponse) {
        if (arq.tipo == 3) {  // Para texto
            arq.dados = await cachedResponse.text();
        } else {
            const blob = await cachedResponse.blob();
            arq.dados = URL.createObjectURL(blob);
        }
        return;
    }

    // Se não estiver no cache, faz o fetch e armazena no cache
    return fetch(arq.link)
        .then(async function (response) {
            if (!response.ok) throw new Error('Erro no Download do Arquivo '+arq.link);
            const responseClone = response.clone();
            if (arq.tipo == 3) { // Para texto,
                arq.dados = await response.text();
            }else{
                const myBlob = await response.blob();
                arq.dados = URL.createObjectURL(myBlob); // Cria um URL a partir do blob
            }
            await cache.put(arq.link, responseClone);
        })
        .catch(e=>{
            arq.erro = e;
        });
}


async function carregarLista() {

    document.querySelector("#butCarregar").remove();
    document.querySelector("#canvas").innerHTML = "";

    let lstPromise = [];
    for (const arq of lstArquivos) {
        arq.dados = null;
        arq.erro = null;
        lstPromise.push( baixarArquivo(arq) );
    }

    await Promise.all(lstPromise)

    let erros = false;
    for (const arq of lstArquivos) {
        if(arq.erro){
            document.querySelector("#canvas").innerHTML += `<div>${arq.link} :> ${arq.erro}</div>`;
            erros = true;
        }
    }
    if(erros) {
        // Em caso de erro coloca novamente o butão Carregar
        document.body.insertAdjacentHTML('afterbegin', '<button id="butCarregar" onclick="carregarLista()">Carregar Lista</button>');
        return;
    }
    mostrarArquivo();
}

var pAtual = 0;
function mostrarArquivo() {
    pararTempo();

    var arq = lstArquivos[pAtual];
    var objectURL = arq.dados;
    if(arq.tipo == 1){
        document.querySelector("#canvas").innerHTML = `<img src="${objectURL}" alt="${arq.link}">`;
        iniciarTempo();
    }

    if(arq.tipo == 2){
        document.querySelector("#canvas").innerHTML = `<video onended="mostrarArquivo()" src="${objectURL}" autoplay muted ></video>`;
    }

    if(arq.tipo == 3){
        document.querySelector("#canvas").innerHTML = `${objectURL}`;
        iniciarTempo();
    }

    pAtual++;
    if (pAtual > lstArquivos.length-1)
        pAtual = 0;
}

var intervalos = 0;
function iniciarTempo() {
    intervalos = setInterval(mostrarArquivo, 5000);
}
function pararTempo() {
    clearInterval(intervalos);
}