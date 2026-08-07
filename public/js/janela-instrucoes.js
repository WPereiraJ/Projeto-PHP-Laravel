// Função para fechar (esconder) a janela
function fecharJanelaInstrucoes() {
    var janela = document.getElementById('janela-instrucoes');
    if (janela) {
        janela.style.display = 'none';
    }
}

// Lógica de Drag & Drop (Arrastar a janela)
document.addEventListener('DOMContentLoaded', function() {
    var janela = document.getElementById("janela-instrucoes");
    
    if (janela) {
        arrastarElemento(janela);
    }

    function arrastarElemento(elmnt) {
        var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
        var cabecalho = document.getElementById("janela-header");
        
        if (cabecalho) {
            cabecalho.onmousedown = iniciarArrasto;
        } else {
            elmnt.onmousedown = iniciarArrasto;
        }

        function iniciarArrasto(e) {
            e = e || window.event;
            e.preventDefault();
            pos3 = e.clientX;
            pos4 = e.clientY;
            document.onmouseup = pararArrasto;
            document.onmousemove = arrastandoElemento;
        }

        function arrastandoElemento(e) {
            e = e || window.event;
            e.preventDefault();
            pos1 = pos3 - e.clientX;
            pos2 = pos4 - e.clientY;
            pos3 = e.clientX;
            pos4 = e.clientY;
            
            elmnt.style.bottom = "auto";
            elmnt.style.right = "auto";
            
            elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
            elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
        }

        function pararArrasto() {
            document.onmouseup = null;
            document.onmousemove = null;
        }
    }
});