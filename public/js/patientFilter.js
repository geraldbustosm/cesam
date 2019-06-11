var tabla = document.getElementById('table-body');
var searchbox = document.getElementById("searchbox");
var searchText;
var timer;

function createRow(dato1,dato2,dato3,dato4,dato5){
    // Create a new row at the end
    var fila = tabla.insertRow(tabla.rows.length);
    var celdas = [];

    // Create cells on the new row
    for(var i=0; i<6; i++){
        celdas[i] = fila.insertCell(i);
        if(i == 0) celdas[i].className = "bold-cell";
    }
    // Adding cells content
    celdas[0].innerHTML = dato1;
    celdas[1].innerHTML = dato2;
    celdas[2].innerHTML = dato3;
    celdas[3].innerHTML = dato4;
    celdas[4].innerHTML = dato5;
    celdas[5].innerHTML =  "<a href='#'><i title='Ver ficha' class='material-icons'>description</i></a><a href='#'><i title='Añadir prestación' class='material-icons'>add</i></a><a href='#' data-toggle='modal' data-target='#exampleModal'><i title='Editar' class='material-icons'>create</i></a><a href='#'><i title='Borrar' class='material-icons'>delete</i></a></td>";
}

// Generate a table with all the patients
function initFill(data){
    for(var i=0;i<data.length;i++){
        createRow(data[i][0],data[i][1],data[i][2],data[i][3],data[i][4]);
    }
}

// Generate a new table whit patients that have 'searchText' on their ID
function filter(data, searchText){
    tabla.innerHTML = "";
    for(var i=0;i<data.length;i++){
        if(data[i][0].includes(searchText)){
            createRow(data[i][0],data[i][1],data[i][2],data[i][3],data[i][4]);
        }
    }
}

// Wait 0.8 sec by every keyup and then call filter function
function search(data){ 
    searchbox.addEventListener("keyup", function(){
        var count = 1;
        clearInterval(timer);
        timer = setInterval(function(){
            count--;
            if(count == 0) {
                searchText = searchbox.value;
                filter(data, searchText);
            }
        }, 800);
    });
}
initFill(pacientes);
search(pacientes);