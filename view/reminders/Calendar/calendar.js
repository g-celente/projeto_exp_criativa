

   //----SELECTABLE-----
   document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            locale:'pt-br',
            navLinks: true, 
            selectable: false,
            selectMirror: true,
            editable: true,
            dayMaxEvents: true, 
            events: '/projeto_exp_criativa/projeto final/view/reminders/Calendar/calendar_list.php',

            eventClick: function(arg) {
                const entry = {
                    id: arg.event.id,
                    nome: arg.event.title,
                    descricao: arg.event.extendedProps.description,
                    data_vencimento: arg.event.startStr,
                    status: arg.event.extendedProps.status,
                    data_pagamento: arg.event.extendedProps.data_pagamento
                };
                handleOpenEditModal(entry);
                const modal = new bootstrap.Modal(document.getElementById('editarModal'));
                modal.show();
            },
        });

        calendar.render();
        const rightHeader = document.querySelector('.fc-header-toolbar .fc-toolbar-chunk:last-child');
        if (rightHeader) {
            const btn = document.createElement('button');
            btn.className = 'btn btn-primary';
            btn.setAttribute('data-bs-toggle', 'modal');
            btn.setAttribute('data-bs-target', '#adicionarModal');
            btn.innerText = 'Novo Lembrete';
            rightHeader.appendChild(btn);
        }

       const handleOpenEditModal = (entry) => {
           document.getElementById('edit-reminder-id').value = entry.id;
           document.getElementById('edit_nome').value = entry.nome;
           document.getElementById('edit_descricao').value = entry.descricao;
           document.getElementById('edit_data_vencimento').value = entry.data_vencimento;

           const pagamentoGroup = document.getElementById('edit-data-pagamento-group');

           if (entry.status !== 'pendente') {
               pagamentoGroup.style.display = '';
               document.getElementById('edit_data_pagamento').value = entry.data_pagamento || '';
           } else {
               pagamentoGroup.style.display = 'none';
               document.getElementById('edit_data_pagamento').value = '';
           }
            document.getElementById('delete-btn-modal').href = './RemindersView_copy.php?action=delete&id=' + entry.id;
       }
    });




