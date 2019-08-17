/***************************************************************************************************************************
                                                VARIABLES
****************************************************************************************************************************/
// Get the table-body for write the list of patients
var tabla = document.getElementById('table-body');
// Get the searchbox element for filter
var searchbox = document.getElementById("searchbox");
/***************************************************************************************************************************
                                                FILL TABLE
****************************************************************************************************************************/
// Write functionarys on the table
function createRow(num, dato1, dato2, dato3, dato4, dato5, dato6) {
    // Create a new row at the end
    var fila = tabla.insertRow(tabla.rows.length);
    // Create cells on the new row
    var celdas = [];
    for (var i = 0; i < 7; i++) {
        celdas[i] = fila.insertCell(i);
        if (i == 0) celdas[i].className = "bold-cell";
    }
    // Getting users
    var user;
    for (var j = 0; j < userArr.length; j++) {
        if (dato4 == userArr[j].id) {
            user = userArr[j].nombre;
        }
    }
    // Getting speciality
    var speciality= "";
    for (var k = 0; k < fsArr.length; k++){
        if (dato1==fsArr[k].funcionarios_id){
            for (var j = 0; j < specialityArr.length; j++) {
                if (fsArr[k].especialidad_id==specialityArr[j].id){
                    speciality+=specialityArr[j].descripcion+"  ";
                }
            }   
        }   
    }
    // Action buttons by active status
    try {
        var active = functionary[0].activa;
        var tmp;
        if (active == 1) {
            tmp = ` <td> 
                    <a href='#'><i title='Ver ficha' class='material-icons'>description</i></a>
                    <a href='#'><i title='Añadir prestación' class='material-icons'>add</i></a>
                    <a href='#' data-toggle='modal' data-target='#exampleModal'><i title='Editar' class='material-icons'>create</i></a>
                    <a name='deletePatient' href='javascript:delPatients()'><i title='Borrar' class='material-icons'>delete</i></a>
                    </td>`
        } else {
            tmp = ` <td>
                    <a href='#'><i title='Ver ficha' class='material-icons'>description</i></a>
                    <a href='#' data-toggle='modal' data-target='#exampleModal'><i title='Editar' class='material-icons'>create</i></a>
                    <a name='activatePatient' href='javascript:actPatients()'><i title='Activar' class='material-icons'>person_add</i></a>
                    </td>`
        }
    } catch (ex) {
        tmp = "";
    }
    // Adding cells content
    celdas[0].innerHTML = num + 1;
    celdas[1].innerHTML = user;
    
    celdas[2].innerHTML = dato2 + ' ' + dato3;
    celdas[3].innerHTML = dato5;
    celdas[4].innerHTML = speciality;
    celdas[5].innerHTML = tmp;    
}
// Generate table with functionarys
function changePage(page) {
    // Validate page so it can't be out of range.
   
    if (page < 1) page = 1;
    if (page > last_page) page = last_page;

    // Clean the table before re-filling
    tabla.innerHTML = "";
    // Re-filling table with functionarys
    for (var i = (page - 1) * records_per_page; i < (page * records_per_page); i++) {
        try {
            // Insert the rows with functionarys info.
            //        1  2                   3                        4                          5                        6                        
            createRow(i, curArray[i].id, curArray[i].nombre1, curArray[i].apellido1, curArray[i].user_id, curArray[i].profesion );
        } catch (err) {
            // We exit if don't have equal number of functionarys and records for page.
            break;
        }
    }
}
/***************************************************************************************************************************
                                                FILTER FUNCTIONARYS
****************************************************************************************************************************/
// Generate a new table whit Functionarys that have 'searchText' on their ID
function filter(searchText) {
    // Create a variable for Functionarys matches with searchText, and another variable for the possition in the new array
    var newFunctionarys = [];
    var pos = 0;
    for (var i = 0; i < fullArray.length; i++) {
        // Compare id with searchText
        if (fullArray[i].id.toString().includes(searchText)) {
            // If it matches then add the functionary in new array, and change the possition
            newFunctionarys[pos] = fullArray[i];
            pos++;
        }
    }
    // Set Funtionary (global variable) with the new array
    curArray = newFunctionarys;
    init(1);
}
// Wait 0.8 sec by every keyup and then call filter function
function search(data) {
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
/***************************************************************************************************************************
                                                LOAD FUNCTIONS
****************************************************************************************************************************/
function init(page) {
    //Table
    changePage(page);
    //Pagination
    numPages();
    numPerPagination();
    aListener();
    //Actions
    delPatients();
    actPatients();
}
//Start
init(1);
search(fullArray);