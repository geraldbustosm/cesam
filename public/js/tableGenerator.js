// Get data from table
var tabla = document.getElementById('table-body');
var n = document.getElementsByTagName('th');
// Get the buttons of pagination
var btn_prev = document.getElementById("btn_prev");
var pagNav = document.getElementById('paginate');
var tagA = document.getElementsByName('tagA');
// Gobal variables
var current_page = 1;
var records_per_page = 6;
var last_page = 1;

function createRow(num, dato) {
    // Create a new row at the end
    var fila = tabla.insertRow(tabla.rows.length);
    var celdas = [];

    // Create cells on the new row
    for (var i = 0; i < n.length; i++) {
        celdas[i] = fila.insertCell(i);
        if (i == 0) celdas[i].className = "bold-cell";
    }

    // Adding cells content
    celdas[0].innerHTML = num + 1;
    celdas[1].innerHTML = dato;
    celdas[2].innerHTML = "<a href='#' data-toggle='modal' data-target='#exampleModal'><i title='Editar' class='material-icons'>create</i></a><a href='#'><i title='Borrar' class='material-icons'>delete</i></a></td>";
}

// Go to prev page in pagination
function prevPage() {
    if (current_page > 1) {
        current_page--;
        init(current_page);
    }
}

// Go to next page in pagination
function nextPage() {
    if (current_page < last_page) {
        current_page++;
        init(current_page);
    }
}

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
            createRow(i, Arr[i].descripcion);
        } catch (err) {
            // We exit if don't have equal number of Arr and records for page.
            break;
        }
    }
}

// Event listener for the number list of pagination (tag <a>)
function aListener() {
    for (var i = 0; i < tagA.length; i++) {
        tagA[i].addEventListener("click", function () {
            current_page = Number(this.id);
            init(current_page);
        });
    }
}

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

// Calculate max number of pages in pagination
function numPages() {
    last_page = Math.ceil(Arr.length / records_per_page);
}

function init(page) {
    numPages();
    changePage(page);
    numPerPagination();
    aListener();
}

init(1);