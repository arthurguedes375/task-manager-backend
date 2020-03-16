var query = location.search.slice(1);
var partes = query.split('&');
var data = {};
partes.forEach(function (parte) {
    var chaveValor = parte.split('=');
    var chave = chaveValor[0];
    var valor = chaveValor[1];
    data[chave] = valor;
});

// console.log(data.id); 

$.ajax({
    type: "GET",
    url: "http://localhost/taskmanager/backend/task/edit",
    data: {
        id: data.id
    },
    dataType: "json",
    success: function (response) {
        console.log(response);
    }
});