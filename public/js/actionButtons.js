/***************************************************************************************************************************
                                                    ACTION BUTTONS
****************************************************************************************************************************/
// Set value on Form
function setValue(id, idForm) {
    // Get hidded input for submit
    var tagID = document.getElementById(idForm);
    // Set value with id
    tagID.value = id;
}
// Check modal for continue
function confirmAction(id, idForm) {
    // Show modal
    $('#confirmModal').modal('show');
    // Get continue button from modal
    var btn = document.getElementById('continueBtn');
    // When is clicked
    btn.addEventListener("click", function() {
        // Call function set Value
        setValue(id, idForm);
        // Submit the data
        document.onSubmit.submit();
    });
}
// Add attendace to the patient
function addAttendance(DNI) {
    setValue(DNI, 'DNI_stage')
    document.onSubmitStage.submit();
}
// Buttons
function changeStatus(id) {
    confirmAction(id, 'id');
}

// Buttons
function changeRol(id) {
    // Call function set Value
    setValue(id, 'rol');
    // Submit the data
    document.onSubmitRol.submit();
}
/********************************************************END*******************************************************************/