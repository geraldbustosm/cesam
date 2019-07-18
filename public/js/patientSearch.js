var searchbox = document.getElementById('searchbox');
var table = document.getElementById('table-body');

// First search to show on the first load (like operator empty: all coincidences)
showPatients("");

// Search event to show results when keyup in the searchbox
searchbox.addEventListener("keyup", function(){
    var search = searchbox.value;
    showPatients(search);
});

// Ajax function
function showPatients(search){
    $.ajax({
        type: 'get',
        url: '/obtenerPacientesAjax',
        data: {'busqueda': search},
        success: function(data){
            table.innerHTML = data;

        },
        error: function(){
            console.log("error");
        }
    });
}