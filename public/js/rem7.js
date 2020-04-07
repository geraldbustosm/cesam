/***************************************************************************************************************************
                                                    VARIABLES
****************************************************************************************************************************/
// Gobal variables
var list = list;
var provenances = provenances;
/***************************************************************************************************************************
                                                    FUNCTIONS
****************************************************************************************************************************/
/**
 * Listener for download button
 */

//trigger download of data.xlsx file
$("#download-xlsx").click(function () {
    table.download("xlsx", "data.xlsx", {
        sheetName: "Reporte"
    });
});

/**
 * Fill tabulator with Groups
 * The information is in the original query
 */
function FillPerSex() {
    table.addColumn({ //create column group
        title: `POR SEXO`,
        columns: [
            { title: `Hombres`, field: `Hombres`, width: 150, bottomCalc: "sum" },
            { title: `Mujeres`, field: `Mujeres`, width: 150, bottomCalc: "sum" },
        ],
    }, false);
}

function FillUnassistances() {
    table.addColumn({ //create column group
        title: `INASISTENTE A CONSULTA MÉDICA (NSP)`,
        columns: [
            { title: `NUEVAS`, field: `nuevo`, width: 150, bottomCalc: "sum" },
            { title: `CONTROLES`, field: `repetido`, width: 150, bottomCalc: "sum" },
        ],
    }, false);
}

/**
 * Fill Tabulator table with some helper data
 * With data from other querys or arrays
 */
function FillPerProvenanceAndAge() {
    // Generate the sub-columns for each macro-column
    colYoung = new Array();
    colOld = new Array();
    provenances.forEach(element => colYoung.push({ title: `${element.descripcion}`, field: `${element.descripcion}_m`, width: 150, bottomCalc: "sum" }));
    provenances.forEach(element => colOld.push({ title: `${element.descripcion}`, field: `${element.descripcion}_M`, width: 150, bottomCalc: "sum" }));
    // Macro-Columns
    table.addColumn({ //create column group
        title: `Menos de 15 años`,
        columns: colYoung,
    }, false);
    table.addColumn({ //create column group
        title: `De 15 y más años`,
        columns: colOld,
    }, false);
}

function FillBenefits() {
    for (i = 0; i < list.length; i++) {
        table.addColumn({ title: `${list[i]}`, field: `${list[i]}`, width: 150, bottomCalc: "sum" }, false);
    };
    table.addColumn({ //create column group
        title: `A BENEFICIARIOS`,
        columns: [
            { title: `Menos de 15 años`, field: `menores`, width: 150, bottomCalc: "sum" },
            { title: `15 años y más`, field: `mayores`, width: 150, bottomCalc: "sum" },
        ],
    }, false);
};

/***************************************************************************************************************************
                                                    LOAD FUNCTIONS
****************************************************************************************************************************/
$('document').ready(function () {
    FillBenefits();
    FillPerSex();
    FillPerProvenanceAndAge();
    FillUnassistances();
})