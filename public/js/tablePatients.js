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
    // Getting info from patient
    var sex = getGender(data);
    // Adding cells content
    celdas[0].innerHTML = num + 1;
    celdas[1].innerHTML = writeRut(data.DNI);
    celdas[2].innerHTML = data.nombre1 + ' ' + data.apellido1 + ' ' + data.apellido2;
    celdas[3].innerHTML = sex;
    celdas[4].innerHTML = getAge(data.fecha_nacimiento);
    celdas[5].innerHTML = getPrevition(data);
    celdas[6].innerHTML = writeActionButtons(data);
}
// Write DNI like rut standar format
function writeRut(DNI) {
    var tmpstr = '';
    for (i = DNI.length; 0 < i + 1; i--) {
        if (i == DNI.length - 1) {
            tmpstr = '-' + DNI.charAt(i);
        } else if (i == DNI.length - 4) {
            tmpstr = '.' + DNI.charAt(i) + tmpstr;
        } else if (i == DNI.length - 7) {
            tmpstr = '.' + DNI.charAt(i) + tmpstr;
        } else if (i == DNI.length - 10) {
            tmpstr = '.' + DNI.charAt(i) + tmpstr;
        } else {
            tmpstr = DNI.charAt(i) + tmpstr
        }
    }
    return tmpstr;
}
// Gender of patient
function getGender(data) {
    // Getting sex
    var gender;
    for (var j = 0; j < sexArr.length; j++) {
        if (data.sexo_id == sexArr[j].id) {
            // Get the name of gender / sexuality
            gender = sexArr[j].descripcion;
        }
    }
    return gender;
}
// Previton of patient
function getPrevition(data) {
    var prev;
    for (var k = 0; k < prevArr.length; k++) {
        if (data.prevision_id == prevArr[k].id) {
            // Get the name of prevition
            prev = prevArr[k].descripcion;
        }
    }
    return prev;
}
// Action buttons by active status
function writeActionButtons(data) {
    try {
        var active = data.activa;
        var tmp = ` <td> 
        <a href='ficha/${data.DNI}'><i title='Ver ficha' class='material-icons'>description</i></a>
        <a href='pacientes/edit/${data.DNI}'><i title='Editar' class='material-icons'>create</i></a>`;
        if (active == 1) {
            // Generate action buttons for active patients
            tmp += `<a href='javascript:addAttendance(${data.DNI})'><i title='Añadir prestación' class='material-icons'>add</i></a>
                    <a href='javascript:changeStatus(${data.DNI})'><i title='Borrar' class='material-icons'>delete</i></a>
                    </td>`
        } else {
            // Generate action buttons for deactive patients
            tmp += `<a href='javascript:changeStatus(${data.DNI})'><i title='Activar' class='material-icons'>person_add</i></a>
                    </td>`
        }
    } catch (ex) {
        tmp = "";
    }
    return tmp
}
// Calculate how many years have the patient
function getAge(bdate) {
    // Variables
    var fullAge;
    var currDate = new Date();
    var birthdate = new Date(bdate);
    // Calc age and months
    var age = currDate.getFullYear() - birthdate.getFullYear();
    var m = currDate.getMonth() - birthdate.getMonth();
    // Sustract one year to age if we aren't on same month of the year
    if (m < 0 || (m === 0 && currDate.getDate() < birthdate.getDate())) {
        age--;
    }
    // Set result
    fullAge = age + ' años';
    // If we have 0 years, return the months
    if (age == 0) {
        fullAge = m + ' meses';
    }
    return fullAge;
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