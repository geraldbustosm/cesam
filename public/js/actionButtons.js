/***************************************************************************************************************************
                                                    ACTION BUTTONS
****************************************************************************************************************************/
// Set value on Form
function setValue(UID, idForm) {
    var tagID = document.getElementById(idForm);
    tagID.value = UID;
}
// Check modal for continue
function confirmAction(UID, idForm) {
    $('#confirmModal').modal('show')
    var btn = document.getElementById('continueBtn');
    btn.addEventListener("click", function() {
        setValue(UID, idForm);
        document.onSubmit.submit();
    });
}
// Deactivate the patient
function delPatient(DNI) {
    confirmAction(DNI, 'DNI');
}
// Deactivate the functionary
function delFunctionary(id) {
    confirmAction(id, 'id');
}
// Reactivate the patient
function actPatient(DNI) {
    confirmAction(DNI, 'DNI');
}
// Reactivate the functionary
function actFunctionary(id) {
    confirmAction(id, 'id')
}
// Add attendace to the patient
function addAttendance(DNI) {
    setValue(DNI, 'DNI_stage')
    document.onSubmitStage.submit();
}
/********************************************************END*******************************************************************/