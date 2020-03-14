/***************************************************************************************************************************
                                                    VARIABLES
****************************************************************************************************************************/
var alertDanger = document.getElementById("danger");
var alertSuccess = document.getElementById("success");
var btn = document.getElementById('btnSubmit');
// Setting alerts display = none
alertDanger.style.display = "none";
alertSuccess.style.display = "none";
/***************************************************************************************************************************
                                                    MAIN FUNCTION
****************************************************************************************************************************/
// Start the validation
function validator() {
    // Listener for submit
    btn.addEventListener("click", function () {
        // Reset alerts
        alertDanger.style.display = "none";
        alertSuccess.style.display = "none";
        // Getting button for submit, country and Rut / Passport
        var id = document.getElementById('dni');
        var pais = document.getElementById('pais');
        var status = checkCountry(id, pais);
        console.log(status);
        if (status) {
            alertSuccess.style.display = "block";
            document.onSubmit.submit();
        } else {
            alertDanger.style.display = "block";
        }
    });
}
/***************************************************************************************************************************
                                                    CHECK SECTION
****************************************************************************************************************************/
// Check origin country
function checkCountry(id, country) {
    if (country.value.toLowerCase().includes('chile')) {
        console.log(country.value.toLowerCase());
        // For Chilean check rut
        return CheckRUT(id);
    } else {
        return true;
    }
}
// Check RUT
function CheckRUT(object) {
    var tmpstr = "";
    var intlen = object.value

    if (intlen.length > 0) {
        crut = object.value
        len = crut.length;

        if (len < 2) {
            object.focus()
            return false;
        }

        for (i = 0; i < crut.length; i++)
            if (crut.charAt(i) != ' ' && crut.charAt(i) != '.' && crut.charAt(i) != '-') {
                tmpstr = tmpstr + crut.charAt(i);
            }
        rut = tmpstr;
        crut = tmpstr;
        len = crut.length;

        if (len > 2)
            rut = crut.substring(0, len - 1);
        else
            rut = crut.charAt(0);

        dv = crut.charAt(len - 1);

        if (rut == null || dv == null)
            return 0;

        var dvr = '0';
        add = 0;
        mul = 2;

        for (i = rut.length - 1; i >= 0; i--) {
            add = add + rut.charAt(i) * mul;
            if (mul == 7)
                mul = 2;
            else
                mul++;
        }

        sub = add % 11;
        if (sub == 1)
            dvr = 'k';
        else if (sub == 0)
            dvr = '0';
        else {
            dvi = 11 - sub;
            dvr = dvi + "";
        }

        if (dvr != dv.toLowerCase()) {
            object.focus()
            return false;
        }
        object.focus()
        return true;
    }
}
/***************************************************************************************************************************
                                                    LOAD FUNCTIONS
****************************************************************************************************************************/
validator()