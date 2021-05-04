let form = document.querySelector("#form-login"),
	ajax = new XMLHttpRequest()
;

window.onload = function() {

    form.addEventListener('submit', event => {
        event.preventDefault();

        let formData = new FormData(form),
            objReq = {
                'username': formData.get('username'),
                'password': btoa(formData.get('password')) 
            }
        ;

		ajax.open("GET", window.location.href+`enter/?${new URLSearchParams(objReq).toString()}`, true);
		ajax.responseType = "text";
		ajax.send();
		ajax.onreadystatechange = function() {
			try {
				let data = JSON.parse(ajax.responseText);

				switch(data.status){

					case 200: 
						let newUrl =  window.location.href + 'dashboard';
						window.location.href = newUrl;
					break;
	
					case 400: 
						toastr.warning('Seu usuário não foi encontrado!', 'Desculpe!');
					break;	
				}
			}catch (ex) {
				// console.log(ex);
			}
		}
    });

	window.history.pushState("", "Inicio", '/quokka/');
};