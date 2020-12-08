/**
 * Script simples para popular as tabelas do dashboard.
 * 
 * @author Vinícius Lima <vinicius.c.lima03@gmail.com>
 */

if (window.XMLHttpRequest) { // Mozilla, Safari, IE7+ ...
    httpRequest = new XMLHttpRequest();
} else if (window.ActiveXObject) { // IE 6 and older
    httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
}

let URLListagem = '../../api/buscaArquivos.php';
let URLDelete = '../../api/deletaArquivos.php';

httpRequest.onreadystatechange = function() {
    if (httpRequest.readyState === XMLHttpRequest.DONE && httpRequest.status === 200) {
        let json = JSON.parse(httpRequest.responseText);
        populateTable(json);
    } else {
        
    }
}

httpRequest.open('GET', URLListagem, true);
httpRequest.send();

/**
 * Função básica para popular a tabela.
 */

function populateTable(json) {
    // Limpa os nodes existentes
    if (document.querySelector('#files tbody tr')) {
        document.querySelector('#files tbody tr').remove();
    }

    for (let i = 0; i < json.length; i++) {
        let tr  = document.createElement('tr'), 
            td1 = document.createElement('td'),
            td2 = document.createElement('td'),
            td3 = document.createElement('td'),
            td4 = document.createElement('td'),
            a   = document.createElement('a');

        td1.textContent = json[i].id;
        td1.setAttribute('scope', 'row');
        td2.textContent = json[i].name;
        a.innerText = 'Remover';
        a.setAttribute('class', 'btn');
        a.setAttribute('id', json[i].type + '-' + json[i].id);
        a.addEventListener('click', deleteFolderOrFile);
        
        td3.textContent = json[i].type === 'folder' ? 'Pasta' : 'Arquivo';

        td4.append(a);

        tr.append(td1, td2, td3, td4);

        tr.setAttribute('id', json[i].id);

        document.querySelector('#files tbody').append(tr);
    }
}

function deleteFolderOrFile(event) {
    let data = event.target.getAttribute('id').split('-');

    if (data[0] != undefined && data[1] !== undefined) {
        let request = new XMLHttpRequest();

        request.onreadystatechange = function() {
            if (request.readyState === XMLHttpRequest.DONE && request.status === 204) {
                alert('Removido com sucesso!');
                window.location.reload();
            } else if (request.readyState === XMLHttpRequest.DONE) {
                alert('Ocorreu um erro ao excluir o arquivo selecionado!');
            }
        }

        request.open("POST", URLDelete, true);
        request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        request.send("type=" + data[0] + "&id=" + data[1]);
    }
}