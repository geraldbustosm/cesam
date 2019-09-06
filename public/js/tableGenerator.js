/***************************************************************************************************************************
                                                    VARIABLES
****************************************************************************************************************************/
// Get data from table
var tabla = document.getElementById('table-body');
var maxColumns = document.getElementsByTagName('th').length;
/***************************************************************************************************************************
                                                    FILL TABLE
****************************************************************************************************************************/
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
function createRow(num, data) {
    // Create a new row at the end
    var fila = tabla.insertRow(tabla.rows.length);
    var celdas = [];

    // Create cells on the new row
    for (var i = 0; i < maxColumns; i++) {
        celdas[i] = fila.insertCell(i);
        if (i == 0) celdas[i].className = "bold-cell";
    }

    // Adding cells content
    celdas[0].innerHTML = num + 1;
    celdas[1].innerHTML = data.descripcion;
    celdas[2].innerHTML = `
        <a href='#'><i title='Editar' class='material-icons'>create</i></a>
        <a href='#'><i title='Borrar' class='material-icons'>delete</i></a></td>`;
}
/***************************************************************************************************************************
                                                    LOAD FUNCTIONS
****************************************************************************************************************************/
function init(page) {
    numPages();
    changePage(page);
    numPerPagination();
    aListener();
}

init(1);
/********************************************************END*******************************************************************/