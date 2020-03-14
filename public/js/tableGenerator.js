/***************************************************************************************************************************
                                                    VARIABLES
****************************************************************************************************************************/
// Get data from table
var tabla = document.getElementById('table-body');
var maxColumns = document.getElementsByTagName('th').length;
// Get the searchbox element for filter
var searchbox = document.getElementById("searchbox");
/***************************************************************************************************************************
                                                    FILL TABLE
****************************************************************************************************************************/
// Re-write all table when change page on pagination
function changePage(page) {
    // Validate page so it can't be out of range.
    if (page < 1) page = 1;
    if (page > last_page) page = last_page;
    // Clean the table before re-filling
    tabla.innerHTML = "";
    // Re-filling table with Arr
    for (var i = (page - 1) * records_per_page; i < (page * records_per_page); i++) {
        try {
            // Insert the rows with Arr info.
            createRow(i, curArray[i]);
        } catch (err) {
            // We exit if don't have equal number of Arr and records for page.
            break;
        }
    }
}
// Write values on cells
function createRow(num, data) {
    // Create a new row at the end
    var fila = tabla.insertRow(tabla.rows.length);
    var celdas = [];
    // Create cells on the new row
    for (var i = 0; i < maxColumns; i++) {
        celdas[i] = fila.insertCell(i);
        if (i == 0) celdas[i].className = "bold-cell";
    }
    if (data.descripcion) {
        if (data.actividad_abre_canasta) {
            // If have 'descripcion' is a 'simple data'
            var descripcion = data.descripcion;
            var canasta = (data.actividad_abre_canasta == 1) ? "Si" : "No";
            // Adding cells content
            celdas[0].innerHTML = num + 1;
            celdas[1].innerHTML = descripcion;
            celdas[2].innerHTML = canasta;
            celdas[3].innerHTML = `<a href='/${table.toLowerCase()}/edit/${data.id}'><i title='Editar' class='material-icons'>create</i></a>`;
            if (data.activa == 1) {
                celdas[3].innerHTML += `<a href='javascript:changeStatus(${data.id})'><i title='Borrar' class='material-icons'>delete</i></a></td>`;
            } else {
                celdas[3].innerHTML += `<a href='javascript:changeStatus(${data.id})'><i title='Activar' class='material-icons'>add</i></a></td>`;
            }
        } else {
            // If have 'descripcion' is a 'simple data'
            var descripcion = data.descripcion;
            // Adding cells content
            celdas[0].innerHTML = num + 1;
            celdas[1].innerHTML = descripcion;
            if (!descripcion.includes('Hombre') && !descripcion.includes('Mujer'))
                celdas[2].innerHTML = `<a href='/${table.toLowerCase()}/edit/${data.id}'><i title='Editar' class='material-icons'>create</i></a>`;
            
            if (data.activa == 1) {
                celdas[2].innerHTML += `<a href='javascript:changeStatus(${data.id})'><i title='Borrar' class='material-icons'>delete</i></a></td>`;
            } else {
                celdas[2].innerHTML += `<a href='javascript:changeStatus(${data.id})'><i title='Activar' class='material-icons'>add</i></a></td>`;
            }
        }
    } else {
        // else is a provision
        var glosa = data.glosaTrasadora;
        // Adding cells content
        celdas[0].innerHTML = num + 1;
        celdas[1].innerHTML = glosa;
        celdas[2].innerHTML = data.codigo;
        celdas[3].innerHTML = `<a href='/prestaciones/edit/${data.id}'><i title='Editar' class='material-icons'>create</i></a>`;
        if (data.activa == 1) {
            celdas[3].innerHTML += `<a href='javascript:changeStatus(${data.id})'><i title='Borrar' class='material-icons'>delete</i></a></td>`;
        } else {
            celdas[3].innerHTML += `<a href='javascript:changeStatus(${data.id})'><i title='Activar' class='material-icons'>add</i></a></td>`;
        }
    }
}
/***************************************************************************************************************************
                                                FILTER DATA
****************************************************************************************************************************/
// Generate a new table whit Data that have 'searchText' on their ID
function filter(searchText) {
    // Create a variable for Data matches with searchText, and another variable for the possition in the new array
    var newArray = [];
    var pos = 0;
    for (var i = 0; i < fullArray.length; i++) {
        // Compare id with searchText
        if (fullArray[i].descripcion.toLowerCase().includes(searchText.toLowerCase())) {
            // If it matches then add the Data in new array, and change the possition
            newArray[pos] = fullArray[i];
            pos++;
        }
    }
    // Set DataArray (global variable) with the new array
    curArray = newArray;
    init(1);
}
// Wait 0.8 sec by every keyup and then call filter function
function search() {
    // Listener for every keyup
    searchbox.addEventListener("keyup", function () {
        // Reset count and release timer
        var count = 1;
        clearInterval(timer);
        // Start count of 0.8 sec for do the filter
        var timer = setInterval(function () {
            count--;
            if (count == 0) {
                // Get text from searchbox item (id of tag)
                var searchText = searchbox.value;
                // Filter data by searchText
                filter(searchText);
            }
            // 800 = 0.8 sec
        }, 800);
    });
}
// Navigation bar for registMain view
function topNav() {
    // Get top navbar element
    var nav = document.getElementById('topNav');
    var tagA = nav.getElementsByTagName('a');
    // Check on wich view we are (searching table)
    for (i = 0; i < nav.childElementCount; i++) {
        if (tagA[i].firstChild.nodeValue == table) {
            // Mark the tag with the same table name with tag
            tagA[i].className += " active";
        } else {
            // For everything else unmark
            tagA[i].className = "nav-link";
        }
    }
}
/***************************************************************************************************************************
                                                    LOAD FUNCTIONS
****************************************************************************************************************************/
function init(page) {
    numPages();
    changePage(page);
    numPerPagination();
    aListener();
    try {
        topNav();
    } catch { }
}

init(1);
search();
/********************************************************END*******************************************************************/