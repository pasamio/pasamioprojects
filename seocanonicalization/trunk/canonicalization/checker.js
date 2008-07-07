function validateDomain(baseloc) {
	element = document.getElementById('paramscorrect_host');
	if(element.value) {
		frame = document.getElementById('DomainCheckFrame');
		frame.src = baseloc + '/plugins/system/canonicalization/validator.php?target=' + element.value;
		
	} else {
		alert('No domain specified');
	}
}