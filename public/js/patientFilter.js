/***************************************************************************************************************************
                                                VARIABLES
****************************************************************************************************************************/
// Get the table-body for write the list of patients
var tabla = document.getElementById('table-body');
// Get the searchbox element for filter
var searchbox = document.getElementById("searchbox");
// Get the buttons of pagination
var btn_prev = document.getElementById("btn_prev");
var pagNav = document.getElementById('paginate');
var tagA = document.getElementsByName('tagA');
// Get elements for action buttons
var delPatient = document.getElementsByName('deletePatient');
var actPatient = document.getElementsByName('activatePatient');
// Gobal variables
var current_page = 1;
var records_per_page = 7;
var patients = patientsArr;
var last_page = 1;
/***************************************************************************************************************************
                                                FILL TABLE
****************************************************************************************************************************/
// Write patients on the table
function createRow(num, dato1, dato2, dato3, dato4, dato5, dato6, dato7) {
    // Create a new row at the end
    var fila = tabla.insertRow(tabla.rows.length);
    // Create cells on the new row
    var celdas = [];
    for (var i = 0; i < 7; i++) {
        celdas[i] = fila.insertCell(i);
        if (i == 0) celdas[i].className = "bold-cell";
    }
    // Getting sex
    var sex;
    for (var j = 0; j < sexArr.length; j++) {
        if (dato5 == sexArr[j].id) {
            sex = sexArr[j].descripcion;
        }
    }
    // Getting prevition
    var prev;
    for (var k = 0; k < prevArr.length; k++) {
        if (dato7 == prevArr[k].id) {
            prev = prevArr[k].nombre;
        }
    }
    // Action buttons by active status
    try {
        var active = patients[0].activa;
        var tmp;
        if (active == 1) {
            tmp = "<td><a href='#'><i title='Ver ficha' class='material-icons'>description</i></a><a href='#'><i title='Añadir prestación' class='material-icons'>add</i></a><a href='#' data-toggle='modal' data-target='#exampleModal'><i title='Editar' class='material-icons'>create</i></a><a name='deletePatient' href='javascript:delPatients()'><i title='Borrar' class='material-icons'>delete</i></a></td>"
        } else {
            tmp = "<td><a href='#'><i title='Ver ficha' class='material-icons'>description</i></a><a href='#' data-toggle='modal' data-target='#exampleModal'><i title='Editar' class='material-icons'>create</i></a><a name='activatePatient' href='javascript:actPatients()'><i title='Activar' class='material-icons'>person_add</i></a></td>"
        }
    } catch (ex) {
        tmp = "";
    }
    // Adding cells content
    celdas[0].innerHTML = num + 1;
    celdas[1].innerHTML = writeRut(dato1);
    celdas[1].id = dato1;
    celdas[2].innerHTML = dato2 + ' ' + dato3 + ' ' + dato4;
    celdas[3].innerHTML = sex;
    celdas[4].innerHTML = getAge(dato6);
    celdas[5].innerHTML = prev;
    celdas[6].innerHTML = tmp;
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
            createRow(i, patients[i].DNI, patients[i].nombre1, patients[i].apellido1, patients[i].apellido2, patients[i].sexo_id, patients[i].fecha_nacimiento, patients[i].prevision_id);
        } catch (err) {
            // We exit if don't have equal number of patients and records for page.
            break;
        }
    }
}
/***************************************************************************************************************************
                                                FILTER PATIENTS
****************************************************************************************************************************/
// Generate a new table whit patients that have 'searchText' on their ID
function filter(searchText) {
    // Create a variable for patients matches with searchText, and another variable for the possition in the new array
    var newPatients = [];
    var pos = 0;
    for (var i = 0; i < patientsArr.length; i++) {
        // Compare id with searchText
        if (patientsArr[i].id.toString().includes(searchText)) {
            // If it matches then add the patient in new array, and change the possition
            newPatients[pos] = patientsArr[i];
            pos++;
        }
    }
    // Set patients (global variable) with the new array
    patients = newPatients;
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
                                                FUNCTION OF PAGINATION
****************************************************************************************************************************/
// Go to prev page function
function prevPage() {
    if (current_page > 1) {
        current_page--;
        init(current_page);
    }
}
// Go to next page function
function nextPage() {
    if (current_page < last_page) {
        current_page++;
        init(current_page);
    }
}
// Event listener for the number list of pagination (tag <a>)
// Change the current pag
function aListener() {
    for (var i = 0; i < tagA.length; i++) {
        tagA[i].addEventListener("click", function () {
            current_page = Number(this.id);
            init(current_page);
        });
    }
}
// Calculate max number of pages in pagination
function numPages() {
    last_page = Math.ceil(patients.length / records_per_page);
}
/***************************************************************************************************************************
                                                BUTTONS OF PAGINATION
****************************************************************************************************************************/
// Prev pag button
function generatePaginationPrev() {
    // Create <li>
    var listItem = document.createElement('li');
    // Create <a>
    var linkItem = document.createElement('a');
    // Adding class to both tags
    listItem.className += "page-item";
    linkItem.className += "page-link";
    // Adding ref to <a> with the numbre of pagination
    linkItem.href += "javascript:prevPage()";
    var spanItem = document.createElement('span');
    // Adding the number (text) on <a>
    linkItem.appendChild(spanItem);
    // Adding <a> on his own <li>
    listItem.appendChild(linkItem);
    // Finally add <li> item on <ul>
    pagNav.appendChild(listItem);
    spanItem.innerHTML = "&laquo;";
}

// Next pag button
function generatePaginationNext() {
    // Create <li>
    var listItem = document.createElement('li');
    // Create <a>
    var linkItem = document.createElement('a');
    // Adding class to both tags
    listItem.className += "page-item";
    linkItem.className += "page-link";
    // Adding ref to <a> with the numbre of pagination
    linkItem.href += "javascript:nextPage()";
    var spanItem = document.createElement('span');
    // Adding the number (text) on <a>
    linkItem.appendChild(spanItem);
    // Adding <a> on his own <li>
    listItem.appendChild(linkItem);
    // Finally add <li> item on <ul>
    pagNav.appendChild(listItem);
    spanItem.innerHTML = "&raquo;";
}

// Number pag buttons
function generatePaginationNum(n, m) {
    pagNav.innerHTML = ""
    generatePaginationPrev();
    // Iterative method for list item creation
    for (n; n <= m; n++) {
        // Create <li>
        var listItem = document.createElement('li');
        // Create <a>
        var linkItem = document.createElement('a');
        // Adding class to both tags
        linkItem.className += "page-link";
        // Using a conditional for listItem
        if (n == current_page) {
            listItem.className += "page-item active";
        } else {
            listItem.className += "page-item";
        }
        // Adding ref to <a> with the numbre of pagination
        linkItem.id += n;
        linkItem.name += "tagA"
        linkItem.href += "javascript:aListener()";
        // Adding the number (text) on <a>
        linkItem.appendChild(document.createTextNode(n));
        // Adding <a> on his own <li>
        listItem.appendChild(linkItem);
        // Finally add <li> item on <ul>
        pagNav.appendChild(listItem);
    }
    generatePaginationNext();
}

// Rotate the numbres of the pagination, so we see 9 pag always
function numPerPagination() {
    if (current_page < 5) {
        if (last_page < 9) {
            generatePaginationNum(1, last_page);
        } else {
            generatePaginationNum(1, 9);
        }
    } else {
        if (current_page + 3 >= last_page) {
            if (last_page - 8 < 1) {
                generatePaginationNum(1, last_page);
            } else {
                generatePaginationNum(last_page - 8, last_page);
            }
        } else {
            generatePaginationNum(current_page - 4, current_page + 4);
        }
    }
}
/***************************************************************************************************************************
                                                ACTION BUTTONS
****************************************************************************************************************************/
// Inactivate the patient
function delPatients() {
    for (var i = 0; i < delPatient.length; i++) {
        delPatient[i].addEventListener("click", function () {
            var tmp = this.parentElement.parentElement;
            var aux = tmp.children[1].id;
            var n = document.getElementById('DNI');
            n.value = aux;
            document.onSubmit.submit();
        });
    }
}
// Re-Activate the patient
function actPatients() {
    for (var i = 0; i < actPatient.length; i++) {
        actPatient[i].addEventListener("click", function () {
            var tmp = this.parentElement.parentElement;
            var aux = tmp.children[1].id;
            var n = document.getElementById('DNI');
            n.value = aux;
            document.onSubmit.submit();
        });
    }
}
/***************************************************************************************************************************
                                                LOAD FUNCTIONS
****************************************************************************************************************************/
function init(page) {
    // Table
    changePage(page);
    // Pagination
    numPages();
    numPerPagination();
    aListener();
    // Actions
    delPatients();
    actPatients();
}
// Start
init(1);
search(patients);