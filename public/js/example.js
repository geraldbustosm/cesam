
var tabla = document.getElementById('table-body');


var functionarys = functionaryArr;

function createRow() {
    // Create a new row at the end
    var fila = tabla.insertRow(tabla.rows.length);
    // Create cells on the new row
    var celdas = [];
    for (var i = 0; i < 7; i++) {
        celdas[i] = fila.insertCell(i);
        if (i == 0) celdas[i].className = "bold-cell";
    }
    celdas[0].innerHTML = "1";
    celdas[1].innerHTML = "1";
    celdas[2].innerHTML = "1";
    celdas[3].innerHTML = "1";
    celdas[4].innerHTML = "1";
}

function changePage(page) {
    // Validate page so it can't be out of range.
    for(var i = 0; i<functionarys.length;i++){
        createRow(i, functionarys[i].id, functionarys[i].nombre1, functionarys[i].apellido1,functionarys[i].user_id, functionarys[i].profesion );
    }
    /*
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
    */
    
}
