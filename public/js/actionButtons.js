/***************************************************************************************************************************
                                                    ACTION BUTTONS
****************************************************************************************************************************/
// Set value on Form
function setValue(DNI,idForm){
    var tagID = document.getElementById(idForm);
    tagID.value = DNI;
}
// Check modal for continue
function confirmAction(DNI, idForm){
    $('#confirmModal').modal('show')
    var btn = document.getElementById('continueBtn');
    btn.addEventListener("click", function(){
        setValue(DNI,idForm);
        document.onSubmit.submit();
    });
}
// Deactivate the patient
function delPatients(DNI) {
    confirmAction(DNI, 'DNI');
}
// Deactivate the functionary
function delFunctionarys(DNI){
    confirmAction(DNI, 'DNI');
}
// Reactivate the patient
function actPatients(DNI) {
    confirmAction(DNI, 'DNI');
}
// Reactivate the functionary
function actFunctionarys(DNI){
    confirmAction(DNI, 'DNI')
}
// Add attendace to the patient
function addAttendance(DNI){
    setValue(DNI, 'DNI_stage')
    document.onSubmitStage.submit();
}
/********************************************************END*******************************************************************/