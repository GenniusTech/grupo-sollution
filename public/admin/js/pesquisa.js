function copiaLink(botao) {
    var link = botao.getAttribute('data-link');
    var tempInput = document.createElement('input');
    tempInput.value = link;
    document.body.appendChild(tempInput);
    tempInput.select();
    tempInput.setSelectionRange(0, 99999);
    document.execCommand('copy');
    document.body.removeChild(tempInput);

    Swal.fire(
        'Sucesso!',
        'Link de pagamento copiado!',
        'success'
    )
}

$(document).ready(function () {
    $('#exportar').click(function () {
        var tabela = document.getElementById('tabela');
        var wb = XLSX.utils.table_to_book(tabela, { sheet: 'Sheet 1' });
        var wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

        function s2ab(s) {
            var buf = new ArrayBuffer(s.length);
            var view = new Uint8Array(buf);
            for (var i = 0; i < s.length; i++) {
                view[i] = s.charCodeAt(i) & 0xFF;
            }
            return buf;
        }

        var blob = new Blob([s2ab(wbout)], { type: 'application/octet-stream' });
        var url = URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = 'tabela.xlsx';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        setTimeout(function () { URL.revokeObjectURL(url); }, 100);
    });
});

function mascaraData(dataInput) {
    let data = dataInput.value;
    data = data.replace(/\D/g, '');
    data = data.replace(/(\d{2})(\d)/, '$1-$2')
    data = data.replace(/(\d{2})(\d)/, '$1-$2');
    dataInput.value = data;
}
