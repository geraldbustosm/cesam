/***************************************************************************************************************************
                                                    VARIABLES
****************************************************************************************************************************/
var btn = document.getElementById('btnSubmit');
/***************************************************************************************************************************
                                                    MAIN FUNCTION
****************************************************************************************************************************/
// Start the validation
function validator() {
    // Listener for submit
    const form = document.getElementById('onSubmit');
    form.addEventListener('submit', async(e) => {
        // No reaload
        e.preventDefault();
        // Check datepicker if someone type it manually
        if (getThisDate()) {
            // Getting button for submit, country and Rut / Passport
            var id = document.getElementById('dni');
            var pais = document.getElementById('pais');
            await checkCountry(id, pais).then(res => {
                if (res) document.onSubmit.submit();
                else Swal.fire('Error!', `El rut no es válido`, 'error');
            })
        } else Swal.fire('Error!', `Fecha no válida`, 'error');
    });
}
/***************************************************************************************************************************
                                                    CHECK SECTION
****************************************************************************************************************************/
// Check origin country
async function checkCountry(id, country) {
    if (country.value.toLowerCase().includes('chile')) {
        console.log(country.value.toLowerCase());
        // For Chilean validate rut
        return CheckRUT(id);
    } else {
        return true;
    }
}
/***************************************************************************************************************************
                                                    LOAD FUNCTIONS
****************************************************************************************************************************/
validator()