var tabla = document.getElementById('table-body');

function crearFila(dato1,dato2,dato3,dato4,dato5){
    // Creamos una nueva fila que se insertará al final de las demás
    var fila = tabla.insertRow(tabla.rows.length);
    var celdas = [];

    // Creando las celdas de la nueva fila de la tabla
    for(var i=0; i<6; i++){
        celdas[i] = fila.insertCell(i);
        if(i == 0) celdas[i].className = "bold-cell";
    }
    // Añadiendo el contenido de las celdas
    celdas[0].innerHTML = dato1;
    celdas[1].innerHTML = dato2;
    celdas[2].innerHTML = dato3;
    celdas[3].innerHTML = dato4;
    celdas[4].innerHTML = dato5;
    celdas[5].innerHTML =  "<a href='#'><i title='Ver ficha' class='material-icons'>description</i></a><a href='#'><i title='Añadir prestación' class='material-icons'>add</i></a><a href='#' data-toggle='modal' data-target='#exampleModal'><i title='Editar' class='material-icons'>create</i></a><a href='#'><i title='Borrar' class='material-icons'>delete</i></a></td>";
}

function rellenoInicial(data){
    for(var i=0;i<data.length;i++){
        crearFila(data[i][0],data[i][1],data[i][2],data[i][3],data[i][4]);
    }
}

function filtrar(data, busqueda){
    tabla.innerHTML = "";
    for(var i=0;i<data.length;i++){
        if(data[i][0].includes(busqueda)){
            crearFila(data[i][0],data[i][1],data[i][2],data[i][3],data[i][4]);
        }
    }
}

function buscar(data){
    var searchbox = document.getElementById("searchbox");
    searchbox.addEventListener("keyup", function(){
        var busqueda = searchbox.value;
        filtrar(data,busqueda);
    });
}
rellenoInicial(pacientes);
buscar(pacientes);

// Opcional: si se quiere borrar lo que está en el input al refrescar la pagina

// function clearInput() {
//     window.addEventListener("beforeunload", function(){
//         document.getElementById("searchbox").value = "";
//     });
// }
// clearInp();

// DORAT METHOD

// function ocultarFila(busqueda){
//     var tabla = document.getElementById("table-body");
//     var filas = tabla.rows;
    
//     for(var i=0;i<filas.length;i++){
//         var celdas = filas[i].cells;
        
//         // Cuando haya match entre la busqueda y el rut
//         // Se invisibilizará toda esa fila. La celda 0 contiene el rut
//         if (celdas[0].innerHTML.includes(busqueda))
//             filas[i].style.display = "none";
//     }
// }

// ocultarFila("2");