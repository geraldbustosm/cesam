var tabla = document.getElementById('table-body');

function createRow(num, dato) {
    // Create a new row at the end
    var fila = tabla.insertRow(tabla.rows.length);
    var celdas = [];

    // Create cells on the new row
    for (var i = 0; i < 3; i++) {
        celdas[i] = fila.insertCell(i);
        if (i == 0) celdas[i].className = "bold-cell";
    }

    // Adding cells content
    celdas[0].innerHTML = num + 1;
    celdas[1].innerHTML = dato;
    celdas[2].innerHTML = "<a href='#' data-toggle='modal' data-target='#exampleModal'><i title='Editar' class='material-icons'>create</i></a><a href='#'><i title='Borrar' class='material-icons'>delete</i></a></td>";
}

function fillCells(){
    for(i=0;i<Arr.length;i++){
        createRow(i, Arr[i].descripcion)
    }
}

fillCells();