let calendarEl = document.getElementById('calendar'),
    sideBarBtn = document.getElementById('sidebar_btn'),
    form = document.querySelector("#form-scheduling"),
    dateStart = new Date().toLocaleDateString('pt-BR').split("/"),
    calendar
;


const receiveOrPay = (event) => {

    let id = event._def.extendedProps.expiration_id
        operation = event._def.extendedProps.id_service,
        fullname = event._def.extendedProps.fullname,
        date = event._def.extendedProps.formatted_date,
        description = event._def.extendedProps.description,
        sequence = event._def.extendedProps.sequence,
        total = event._def.extendedProps.total
    ;

    vex.defaultOptions.className = 'vex-theme-flat-attack';
    vex.dialog.buttons.YES.text = 'Confirmar';
    vex.dialog.buttons.YES.className = 'btn-dialog-vex';
    vex.dialog.buttons.NO.text = 'Cancelar';

    vex.dialog.confirm({
        input: [`
            <section style="font-size: 14px; word-break: break-word;">
                <div><strong>Serviço: </strong>${operation == "1" ? "Venda" : "Pagamento de Contas"}<div>
                <div><strong>Cliente: </strong>${fullname}<div>
                <div><strong>Data do vencimento: </strong>${date}<div>
                <div><strong>Parcela: </strong>${sequence} de ${total}<div>
                <div><strong>Descrição: </strong>${description}<div>
            </section>
        `].join(''),
        message: `Confirmar o ${operation == "1" ? "recebimento" : "pagamento"} do vencimento abaixo?`,
        callback: (response) => {
            if(response){
                ajax.open("GET", window.location.origin+`/quokka/schedules/confirm/?id=${id}`, true);
                ajax.responseType = "text";
                ajax.send();
                ajax.onreadystatechange = function() {
                	try {
                		let data = JSON.parse(ajax.responseText);
        
                		switch(data.status){
                			case 200: 
                                toastr.success(data.message, 'Sucesso!'); 
                                listEvents();
                            break;
                			case 400: toastr.warning(data.message, 'Erro!'); break;	
                		}
                	}catch (ex) {
                		// console.log(ex);
                	}
                }
            }
        }
    });
}


const buildCalendar = (events) => {
    calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        initialDate: `${dateStart[2]}-${dateStart[1]}-${dateStart[0]}`,
        navLinks: true,
        selectable: true,
        selectMirror: true,
        select: (arg) => {
            localStorage.setItem('date-scheduling', arg.startStr); 
            window.location.href = window.location.origin+`/quokka/schedules/new`;
        },
        eventClick: (arg) => {
            receiveOrPay(arg.event);
        },
        editable: false,
        dayMaxEvents: true, // allow "more" link when too many events
        events: events
    });
    calendar.setOption('locale', 'pt-br');
    calendar.render();
}


const listEvents = () => {
    ajax.open("GET", window.location.origin+`/quokka/schedules/list`, true);
    ajax.responseType = "text";
    ajax.send();
    ajax.onreadystatechange = function() {
        let data = JSON.parse(ajax.responseText);
        if($('#calendar').length > 0) buildCalendar(data);
    }
}


window.onload = function() {

    if($('#calendar').length > 0) listEvents(); 

    if(form){        
        $("input[name=realization_date]").val(localStorage.getItem('date-scheduling'));

        form.addEventListener('submit', event => {
            event.preventDefault();
    
            let formData = new FormData(form),
                objReq = {
                    'id':                   formData.get('id'),
                    'id_service':           formData.get('id_service'),
                    'realization_date':     formData.get('realization_date'),
                    'value':                (formData.get('value').replace(".", "")).replace(",", "."),
                    'id_customer':          formData.get('id_customer'),
                    'id_user':              formData.get('id_user'),
                    'id_form_payment':      formData.get('id_form_payment'),
                    'plots':                formData.get('plots'),
                    'maturities':           formData.get('maturities'),
                    'description':          formData.get('description'),
                }   
            ;
    
            ajax.open("GET", window.location.origin+`/quokka/schedules/create/?${new URLSearchParams(objReq).toString()}`, true);
            ajax.responseType = "text";
            ajax.send();
            ajax.onreadystatechange = function() {
                try {
                    let data = JSON.parse(ajax.responseText);
    
                    switch(data.status){
    
                        case 200:  
                            toastr.success(data.message, 'Sucesso!'); 
                            setTimeout(() => window.location.href = window.location.origin+`/quokka/schedules`, 2000);
                        break;
        
                        case 400: toastr.warning(data.message, 'Erro!'); break;	
                    }
                }catch (ex) {
                    // console.log(ex);
                }
            }
        });
    }


    sideBarBtn.addEventListener('click', () => {
        setTimeout(() => {
            calendar.render();
        }, 500);
    });
}