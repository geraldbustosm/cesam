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
var functionarys = functionaryArr;
var last_page = 1;
/***************************************************************************************************************************
                                                FILL TABLE
****************************************************************************************************************************/
// Write patients on the table
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
            tmp = "<td><a href='#'><i title='Ver ficha' class='material-icons'>description</i></a><a href='#'><i title='Añadir prestación' class='material-icons'>add</i></a><a href='#' data-toggle='modal' data-target='#exampleModal'><i title='Editar' class='material-icons'>create</i></a><a name='deletePatient' href='javascript:delPatients()'><i title='Borrar' class='material-icons'>delete</i></a></td>"
        } else {
            tmp = "<td><a href='#'><i title='Ver ficha' class='material-icons'>description</i></a><a href='#' data-toggle='modal' data-target='#exampleModal'><i title='Editar' class='material-icons'>create</i></a><a name='activatePatient' href='javascript:actPatients()'><i title='Activar' class='material-icons'>person_add</i></a></td>"
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
    
    //celdas[0].innerHTML = "1";
    //celdas[1].innerHTML = "1";
    //celdas[2].innerHTML = "1";
    //celdas[3].innerHTML = "1";
    //celdas[4].innerHTML = "1";
    
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
            //        1  2                  3                       4                        5                       6                        
            createRow(i, functionarys[i].id, functionarys[i].nombre1, functionarys[i].apellido1,functionarys[i].user_id, functionarys[i].profesion );
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
    for (var i = 0; i < functionaryArr.length; i++) {
        // Compare id with searchText
        if (functionaryArr[i].id.toString().includes(searchText)) {
            // If it matches then add the functionary in new array, and change the possition
            newFunctionarys[pos] = functionaryArr[i];
            pos++;
        }
    }
    // Set Funtionary (global variable) with the new array
    functionarys = newFunctionarys;
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
    last_page = Math.ceil(functionarys.length / records_per_page);
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
search(functionarys);