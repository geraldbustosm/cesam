var tabla = document.getElementById('table-body');
var pagNav = document.getElementById('maxPages');
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
    celdas[5].innerHTML =  "<td><a href='#'><i title='Ver ficha' class='material-icons'>description</i></a><a href='#'><i title='Añadir prestación' class='material-icons'>add</i></a><a href='#' data-toggle='modal' data-target='#exampleModal'><i title='Editar' class='material-icons'>create</i></a><a href='#'><i title='Borrar' class='material-icons'>delete</i></a></td>";
}

// Generate a table with all the patients
function initFill(data){
    for(var i=0;i<data.length;i++){
        createRow(data[i].id,data[i].nombre1,data[i].apellido1,data[i].sexo,data[i].fecha_nacimiento);
    }
}

// Generate a new table whit patients that have 'searchText' on their ID
function filter(data, searchText){
    tabla.innerHTML = "";
    for(var i=0;i<data.length;i++){
        if(data[i].id.includes(searchText)){
            createRow(data[i].id,data[i].nombre1,data[i].apellido1,data[i].sexo,data[i].fecha_nacimiento);
        }
    }
}

// Wait 0.8 sec by every keyup and then call filter function
function search(data){
    // Listener for every keyup
    searchbox.addEventListener("keyup", function(){
        // Reset count and release timer
        var count = 1;
        clearInterval(timer);
        // Start count of 0.8 sec for do the filter
        timer = setInterval(function(){
            count--;
            if(count == 0) {
                // Get text from searchbox item (id of tag)
                searchText = searchbox.value;
                // Filter data by searchText
                filter(data, searchText);
            }
        // 800 = 0.8 sec
        }, 800);
    });
}

function pagination(data){
    // Iterative method for list item creation
    for(var i=1;i<=data.last_page;i++){
        // Create <li>
        var listItem = document.createElement('li');
        // Create <a>
        var linkItem = document.createElement('a');
        // Adding class to both tags
        listItem.className += "page-item";
        linkItem.className += "page-link";
        // Adding ref to <a> with the numbre of pagination
        linkItem.href = "testing?page=".concat(i);
        // Adding the number (text) on <a>
        linkItem.appendChild(document.createTextNode(i));
        // Adding <a> on his own <li>
        listItem.appendChild(linkItem);
        // Finally add <li> item on <ul>
        pagNav.appendChild(listItem);
    }
}

pagination(object);
initFill(pacientes);
search(pacientes);