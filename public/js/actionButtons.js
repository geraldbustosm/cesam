// Get elements for action buttons
var delPatient = document.getElementsByName('deletePatient');
var actPatient = document.getElementsByName('activatePatient');
var addAttend = document.getElementsByName('addingAttendance');
/***************************************************************************************************************************
                                                    ACTION BUTTONS
****************************************************************************************************************************/
// Deactivate the patient
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
// Reactivate the patient
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
// Add attendace to the patient
function addAttendance(){
    for (var i = 0; i < addAttend.length; i++) {
        addAttend[i].addEventListener("click", function () {
            var tmp = this.parentElement.parentElement;
            var aux = tmp.children[1].id;
            var n = document.getElementById('DNI_stage');
            n.value = aux;
            document.onSubmitStage.submit();
        });
    }
}
/********************************************************END*******************************************************************/