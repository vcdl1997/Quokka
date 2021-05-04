let form = document.querySelector("#form-customer"),
    table
;

const deleteCustomer = (id) => {
    vex.defaultOptions.className = 'vex-theme-flat-attack';
    vex.dialog.buttons.YES.text = 'Confirmar';
    vex.dialog.buttons.YES.className = 'btn-dialog-vex';
    vex.dialog.buttons.NO.text = 'Cancelar';

    vex.dialog.confirm({
        message: 'Deseja excluir o Cliente?',
        callback: (response) => {
            if(!response) return false;

            ajax.open("GET", window.location.origin+`/quokka/customers/delete/?id=${id}`, true);
            ajax.responseType = "text";
            ajax.send();
            ajax.onreadystatechange = function() {
                try {
                    let data = JSON.parse(ajax.responseText);
    
                    switch(data.status){
    
                        case 200:  
                            toastr.success(data.message, 'Sucesso!'); 
                            table.ajax.reload();
                        break;
        
                        case 400: toastr.warning(data.message, 'Erro!'); break;	
                    }
                }catch (ex) {
                    // console.log(ex);
                }
            }
        }
    });
}


window.onload = function() {

    if($('#customer-list').length > 0){
        table = $('#customer-list').DataTable({			
            "processing": true,
            "serverSide": true,
            "ajax": window.location.href+`/list/`,
            "language": {
                "decimal":        "",
                "emptyTable":     "Nenhum registro encontrado",
                "info":           "Mostrando _START_ de _END_ até _TOTAL_ registros",
                "infoEmpty":      "Mostrando 0 de 0 até 0 registros",
                "infoFiltered":   "(filtrado do total de _MAX_ registros)",
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     "Mostrar _MENU_ registros",
                "loadingRecords": "Carregando...",
                "processing":     "Processando...",
                "search":         "Buscar:",
                "zeroRecords":    "Nenhum registro encontrado",
                "paginate": {
                    "first":      "Primeiro",
                    "last":       "Último",
                    "next":       "Próximo",
                    "previous":   "Anterior"
                },
                "aria": {
                    "sortAscending":  ": ative para classificar a coluna em ordem crescente",
                    "sortDescending": ": ative para classificar a coluna em ordem decrescente"
                }
            },
            columnDefs: [
                { className: 'text-left',   data: 'fullname',       name: 'fullname',       targets: 0 }, 
                { className: 'text-center', data: 'surname',        name: 'surname',        targets: 1 }, 
                { className: 'text-left',   data: 'cell_phone',     name: 'cell_phone',     targets: 2 }, 
                { className: 'text-left',   data: 'email_address',  name: 'email_address',  targets: 3 }, 
                { className: 'text-center', data: 'acoes',          name: 'acoes',      targets: 4,
                    render : function (data, type, row) { 
                        let prefix = document.querySelector("#url-customer-prefix").href;
    
                        return `
                            <div style="display: flex; align-items: center; justify-content: space-around;">
                                <button class="btn-default" onclick="location.href='${prefix}/edit/${row.id}'" title="Editar Usuário">
                                    <i class="fas fa-edit" style="color: white;" title="Editar Usuário"></i>
                                </button>
                                <button class="btn-default" onclick="deleteCustomer(${row.id})" title="Exluir Usuário">
                                    <i class="far fa-trash-alt" style="color: white;" title="Exluir Usuário"></i>
                                </button>
                            <div>
                        `;
                    }
                }
            ]
        });
    }


    if(form){
        $("input[name=home_phone]").mask('(00) 0000-0000');
        $("input[name=cell_phone]").mask('(00) 00000-0000');

        form.addEventListener('submit', event => {
            event.preventDefault();

            let formData = new FormData(form),
                objReq = {
                    'id': formData.get('id'),
                    'fullname':             formData.get('fullname'), 
                    'surname':              formData.get('surname'), 
                    'home_phone':           formData.get('home_phone'), 
                    'cell_phone':           formData.get('cell_phone'), 
                    'contact_for_message':  formData.get('contact_for_message'), 
                    'email_address':        formData.get('email_address'), 
                    'full_address':         formData.get('full_address'), 
                    'birth_date':           formData.get('birth_date')
                }   
            ;

            ajax.open("GET", window.location.origin+`/quokka/customers/save/?${new URLSearchParams(objReq).toString()}`, true);
            ajax.responseType = "text";
            ajax.send();
            ajax.onreadystatechange = function() {
                try {
                    let data = JSON.parse(ajax.responseText);

                    switch(data.status){

                        case 200:  
                            toastr.success(data.message, 'Sucesso!'); 

                            if(window.location.href.indexOf("edit") == -1){
                                setTimeout(() => window.location.href = window.location.origin+`/quokka/customers/edit/${data.id}`, 2000);
                            }
                        break;
        
                        case 400: toastr.warning(data.message, 'Erro!'); break;	
                    }
                }catch (ex) {
                    // console.log(ex);
                }
            }
        });
    }
}