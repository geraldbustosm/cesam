/***************************************************************************************************************************
                                                    ACTION BUTTONS
****************************************************************************************************************************/
// Set value on Form
function setValue(UID, idForm) {
    // Get hidded input for submit
    var tagID = document.getElementById(idForm);
    // Set value with DNI or Rut from functionary
    tagID.value = UID;
}
// Check modal for continue
function confirmAction(UID, idForm) {
    // Show modal
    $('#confirmModal').modal('show');
    // Get continue button from modal
    var btn = document.getElementById('continueBtn');
    // When is clicked
    btn.addEventListener("click", function() {
        // Call function set Value
        setValue(UID, idForm);
        // Submit the data
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