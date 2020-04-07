/***************************************************************************************************************************
                                                    VARIABLES
****************************************************************************************************************************/
// Gobal variables
var list = list;
/***************************************************************************************************************************
                                                    FUNCTIONS
****************************************************************************************************************************/
function FillTable() {
    // Complete table
    for (i = 0; i < list.length; i++) {
        table.addColumn(
            {//create column group
                title: `${list[i]}`,
                columns: [
                    { title: "Hombres", field: `${list[i]} - H`, width: 150, bottomCalc: "sum" },
                    { title: "Mujeres", field: `${list[i]} - M`, width: 150, bottomCalc: "sum" },
                ],
            }, false);
    };

    // Add the last two columns
    table.addColumn({ title: "Beneficiarios", field: "Beneficiarios", width: 150, bottomCalc: "sum" }, false);
    table.addColumn({ title: "Niños, Niñas, Adolescentes y Jóvenes Población SENAME", field: "menoresSENAME", width: 150, bottomCalc: "sum" }, false);
}

/**
 * Listener for download button
 * trigger download of data.xlsx file
 */
$("#download-xlsx").click(function () {
    table.download("xlsx", "data.xlsx", {
        sheetName: "Reporte"
    });
});
/***************************************************************************************************************************
                                                    LOAD FUNCTIONS
****************************************************************************************************************************/
$('document').ready(function () {
    FillTable();
})