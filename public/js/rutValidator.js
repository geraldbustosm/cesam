async function CheckRUT(object) {
    var tmpstr = "";
    var intlen = object.value

    if (intlen.length > 0) {
        crut = object.value
        len = crut.length;

        if (len < 2) {
            object.focus()
            return false;
        }

        tmpstr = await quitarFormato(crut);
        rut = tmpstr;
        crut = tmpstr;
        len = crut.length;

        if (len > 2) rut = crut.substring(0, len - 1);
        else rut = crut.charAt(0);

        dv = crut.charAt(len - 1);

        if (rut == null || dv == null) return false;

        var dvr = '0';
        add = 0;
        mul = 2;

        for (i = rut.length - 1; i >= 0; i--) {
            add = add + rut.charAt(i) * mul;
            if (mul == 7) mul = 2;
            else mul++;
        }

        sub = add % 11;
        if (sub == 1) dvr = 'k';
        else if (sub == 0) dvr = '0';
        else {
            dvi = 11 - sub;
            dvr = dvi + "";
        }

        if (dvr != dv.toLowerCase()) {
            object.focus()
            return false;
        }

        object.focus()
        object.value = tmpstr;
        return true;
    }
}

async function quitarFormato(rut) {
    var strRut = new String(rut);
    while (strRut.indexOf(".") != -1) {
        strRut = strRut.replace(".", "");
    }
    while (strRut.indexOf("-") != -1) {
        strRut = strRut.replace("-", "");
    }
    while (strRut.indexOf(",") != -1) {
        strRut = strRut.replace(",", "");
    }
    return strRut;
}

function formatear(Rut, digitoVerificador) {
    var sRut = new String(Rut);
    var sRutFormateado = '';
    if (digitoVerificador) {
        var sDV = sRut.charAt(sRut.length - 1);
        sRut = sRut.substring(0, sRut.length - 1);
    }
    while (sRut.length > 3) {
        sRutFormateado = "." + sRut.substr(sRut.length - 3) + sRutFormateado;
        sRut = sRut.substring(0, sRut.length - 3);
    }
    sRutFormateado = sRut + sRutFormateado;
    if (sRutFormateado != "" && digitoVerificador) { sRutFormateado += "-" + sDV; } else if (digitoVerificador) { sRutFormateado += sDV; }
    return sRutFormateado;
}