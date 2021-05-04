let ajax = new XMLHttpRequest();


const logout = () => {
	// Sai do sistema
	ajax.open("GET", window.location.origin+`/quokka/exit`, true);
	ajax.responseType = "text";
	ajax.send();
	ajax.onreadystatechange = function() {
		try {
			let data = JSON.parse(ajax.responseText);

			switch(data.status){

				case 200: 
					localStorage.setItem('entrance', false); 
					window.location.href = window.location.origin + '/quokka/';
				break;

				case 400: 
					toastr.error('Parace que houve algum erro, tente novamente.', 'Ops!');
				break;	
			}
		}catch (ex) {
			// console.log(ex);
		}
	}
}

window.onload = () => {

	// Dá as Boas vindas ao Sistema
    if(!localStorage.getItem('entrance') || localStorage.getItem('entrance') == 'false'){
        localStorage.setItem('entrance', true);

		let user = document.querySelector("#name-user").innerText;
		toastr.success(`Seja Bem Vindo: ${user}`, '');
    }
}