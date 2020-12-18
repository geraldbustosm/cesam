/***************************************************************************************************************************
                                                    VARIABLES
****************************************************************************************************************************/
// Get the table-body for write the list of patients
var tabla = document.getElementById('table-body');
// Get the searchbox element for filter
var searchbox = document.getElementById("searchbox");
// Count of titles on table
var maxColumns = document.getElementsByTagName('th').length;
/***************************************************************************************************************************
                                                    FILL TABLE
****************************************************************************************************************************/
// Generate table with patients
function changePage(page) {
    // Validate page so it can't be out of range.
    if (page < 1) page = 1;
    if (page > last_page) page = last_page;

    // Clean the table before re-filling
    tabla.innerHTML = "";
    // Re-filling table with patients

    for (var i = (page - 1) * records_per_page; i < (page * records_per_page); i++) {
        try {
            // Insert the rows with patients info.
            createRow(i, curArray[i]);
        } catch (err) {
            // We exit if don't have equal number of patients and records for page.
            break;
        }
    }
}
// Write patients on the table
function createRow(num, data) {
    // Create a new row at the end
    var fila = tabla.insertRow(tabla.rows.length);
    // Create cells on the new row
    var celdas = [];
    for (var i = 0; i < maxColumns; i++) {
        celdas[i] = fila.insertCell(i);
        if (i == 0) {
            celdas[i].className = "bold-cell";
        }
    }
    // Adding cells content
    celdas[0].innerHTML = num + 1;
    celdas[1].innerHTML = (data.dni ? data.dni : data.DNI);
    if (data.apellido2) {
        celdas[2].innerHTML = data.nombre1 + ' ' + data.apellido1 + (data.apellido2 ? ' ' + data.apellido2 : '');
    } else {
        celdas[2].innerHTML = data.nombre1 + ' ' + data.apellido1;
    }
    celdas[3].innerHTML = data.sexo;
    celdas[4].innerHTML = data.edad;
    celdas[5].innerHTML = data.prevision;
    celdas[6].innerHTML = writeActionButtons(data);
}
// Action buttons by active status
function writeActionButtons(data) {
    try {
        var active = data.activa;
        console.log(data);
        var tmp = ` <td><a href='/ficha/${data.DNI}'><i title='Ver ficha' class='material-icons'>description</i></a>`;
        // Generate action buttons for active patients
        if (active == 1) tmp += `<a href='javascript:addAttendance(${data.id})'><i title='Añadir prestación' class='material-icons'>add</i></a>`
        if (auth.rol == 1) {
            tmp += `<a href='/pacientes/edit/${data.DNI}'><i title='Editar' class='material-icons'>create</i></a>`;
            if (active == 1) tmp += `<a href='javascript:changeStatus(${data.id})'><i title='Borrar' class='material-icons'>delete</i></a> </td>`;
            else tmp += `<a href='javascript:changeStatus(${data.id})'><i title='Activar' class='material-icons'>person_add</i></a> </td>`;
        }
    } catch (ex) {
        console.log(ex);
        tmp = "";
    }
    return tmp
}
/***************************************************************************************************************************
                                                    FILTER PATIENTS
****************************************************************************************************************************/
// Generate a new table with patients that have 'searchText' on their ID
function filter(searchText) {
    // Create a variable for patients matches with searchText, and another variable for the possition in the new array
    var newPatients = [];
    var pos = 0;
    for (var i = 0; i < fullArray.length; i++) {
        // Compare id with searchText
        if (fullArray[i].DNI.toString().includes(searchText) || fullArray[i].nombre1.toLowerCase().includes(searchText) ||
            fullArray[i].apellido1.toLowerCase().includes(searchText) || fullArray[i].apellido2.toLowerCase().includes(searchText)) {
            // If it matches then add the patient in new array, and change the possition
            newPatients[pos] = fullArray[i];
            pos++;
        }
    }
    // Set patients (global variable) with the new array
    curArray = newPatients;
    init(1);
}
// Wait 0.8 sec by every keyup and then call filter function
function search() {
    // Listener for every keyup
    searchbox.addEventListener("keyup", function() {
        // Reset count and release timer
        var count = 1;
        clearInterval(timer);
        // Start count of 0.8 sec for do the filter
        var timer = setInterval(function() {
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
/***************************************************************************************************************************
                                                    LOAD FUNCTIONS
****************************************************************************************************************************/
function init(page) {
    // Table
    changePage(page);
    //Pagination
    numPages();
    numPerPagination();
    aListener();
}
// Start
init(1);
search();
/********************************************************END*******************************************************************/