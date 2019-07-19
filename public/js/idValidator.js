var id = document.getElementById('userID');

// Start the validation
function validator( id )
{
	var validate = document.getElementById('pais');
  validate.addEventListener("keyup", function(){
  	var country = document.getElementById('userCountry');
    checkCountry(id,country.value);
  });
}

// Check origin country
function checkCountry( id, country )
{
	if(country.includes('chile')) {
		CheckRUT(id); // For Chilean check rut
	}
	else {
		// For foreing check UID
	}
}

// Valid the RUT
function CheckRUT( object )
{
	var tmpstr = "";
	var intlen = object.value
	if (intlen.length > 0)
	{
		crut = object.value
		len = crut.length;
		if ( len < 2 )
		{
			alert('rut inválido')
			object.focus()
			return false;
		}
		for ( i=0; i < crut.length ; i++ )
		if ( crut.charAt(i) != ' ' && crut.charAt(i) != '.' && crut.charAt(i) != '-' )
		{
			tmpstr = tmpstr + crut.charAt(i);
		}
		rut = tmpstr;
		crut= tmpstr;
		len = crut.length;
 
		if ( len > 2 )
			rut = crut.substring(0, len - 1);
		else
			rut = crut.charAt(0);
 
		dv = crut.charAt(len-1);
 
		if ( rut == null || dv == null )
		return 0;
 
		var dvr = '0';
		add = 0;
		mul  = 2;
 
		for (i= rut.length-1 ; i>= 0; i--)
		{
			add = add + rut.charAt(i) * mul;
			if (mul == 7)
				mul = 2;
			else
				mul++;
		}
 
		sub = add % 11;
		if (sub==1)
			dvr = 'k';
		else if (sub==0)
			dvr = '0';
		else
		{
			dvi = 11-sub;
			dvr = dvi + "";
		}
 
		if ( dvr != dv.toLowerCase() )
		{
			alert('El Rut Ingreso es Invalido')
			object.focus()
			return false;
		}
		object.focus()
		return true;
	}
}