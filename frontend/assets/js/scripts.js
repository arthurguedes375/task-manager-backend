const base_url = 'https://localhost/taskmanager/tasks/';

//Return task data

let currentTaskId = null;

function getTask(task) {
    $('#addTaskModalLabel').html('Edit Task');
    $('#btn_save_task').html('Save Changes');
    $('#btn_delete_task').show();
    $('#addTaskModal').modal('show');

    currentTaskId = task.id;

    $.ajax({
        type: 'GET',
        url: base_url + task.id,
        success: function (response) {

            $('input#task_title').val(response[0].task);
            $('textarea#task_description').val(response[0].tdescription);
            $('input#task_date').val(response[0].tdate);

            $('#btn_save_task').unbind('click');
            $('#task_form').unbind('submit');
            console.log(response[0].id);
            updateTask(response[0].id);
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Erro ao Buscar Dados da Tarefa!',
                text: "Verifique a sua conexão com a internet! Se estiver tudo ok, Tente mais tarde!",
                footer: 'Nenhuma alteração ou exclusão será salva!'
            })
        }
    });
}

function updateTask(id) {
    $('#task_form').on('submit', function (e) {

        e.preventDefault();

        $.ajax({
            type: 'PUT',
            url: base_url + 'update/' + id,
            data: $(this).serialize(),
            success: function (response) {

                //Atualizando a div task correspondente

                $('div#' + response[0].id + '.task').find('#task_title').html(response[0].task);
                $('div#' + response[0].id + '.task').find('#task_description').html(response[0].tdescription);
                $('div#' + response[0].id + '.task').find('#task_date').html(response[0].tdate);

                $('#addTaskModal').modal('hide'); //Fechando modal


            },
            error: function (response) {
                var response = JSON.parse(response.responseText);
                $('#message_box').removeClass('d-none').html(response.error);
            }
        });
    });

    $('#btn_save_task').on('click', function () {
        $('#task_form').submit();
    });
}

function addTask() {
    //Evento disparado quando o formulário for submetido

    $('#task_form').on('submit', function (e) {

        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: base_url + 'create',
            data: $(this).serialize(),
            success: function (response) {

                var html = '<div class="task" onclick="getTask(this)" id="' + response[0].id + '"><span class="title"><h1 id="task_title">' + response[0].task + '</h1></span><p class="description" id="task_description">' + response[0].tdescription + '</p><span class="date" id="task_date">' + response[0].tdate + '</span></div>';

                $('#tasks_container').append(html); //Adicionando tarefa na tela

                $('#addTaskModal').modal('hide'); //Fechando modal

                Swal.fire({
                    icon: 'success',
                    title: 'Your task has been saved',
                    showConfirmButton: false,
                    timer: 1500
                })

            },
            error: function (response) {
                var response = JSON.parse(response.responseText);
                if (response.error === "Task duplicated!") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Já existe esta Tarefa Cadastrada no nosso Banco de Dados!',
                        showConfirmButton: true,
                    })
                } else if (response.error === "Preencha todos os campos!!!") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Preencha todos os campos!!!',
                        showConfirmButton: true,
                    })
                }
                console.log(response.error);

            }
        });
    });

    $('#btn_save_task').on('click', function () {
        $('#task_form').submit();
    });
}

// Delete Task
function deleteTask() {

    $.ajax({
        type: 'DELETE',
        url: base_url + 'delete/' + currentTaskId,
    });

    $(`#${currentTaskId}`).hide();
    Swal.fire({
        icon: 'success',
        title: 'A sua tarefa foi Deletada com Sucesso!',
        showConfirmButton: false,
        timer: 2000
    })
}

function resetModal() {
    $('#addTaskModalLabel').html('Create Task');
    $('#btn_save_task').html('Create');
    $('#btn_delete_task').hide();
    $('input#task_title').val('');
    $('textarea#task_description').val('');
    $('input#task_date').val('');
    $('#message_box').addClass('d-none');
}

$(document).ready(function () {

    //Get all tasks

    $.ajax({
        type: 'GET',
        url: base_url,
        success: function (response) {

            if (response.success === false) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro ao Buscar Dados!',
                    text: "Verifique a sua conexão com a internet! Se estiver tudo ok, Tente mais tarde!",
                    footer: 'Nenhuma alteração ou exclusão será salva!'
                })

            } else {
                $.each(response, function (key, task) {
                    var html = '<div class="task" onclick="getTask(this)" id="' + task.id + '"><span class="title"><h1 id="task_title">' + task.task + '</h1></span><p class="description" id="task_description">' + task.tdescription + '</p><span class="date" id="task_date">' + task.tdate + '</span></div>';

                    $('#tasks_container').append(html); //Adicionando tarefa na tela
                });
            }
        }
    });

    //Ao clicar no botão de adicionar tarefa, chama a função
    $('#btn_add_task').on('click', function () {
        addTask();
    });

    //Ao clicar no botão de deletar tarefa, chama a função
    $('#btn_delete_task').on('click', function () {
        deleteTask();
    });



    //Sempre que o modal for escondido ou fechado, remove os eventos
    //impedindo que a requisição seja feita mais de uma vez

    $('#addTaskModal').on('hidden.bs.modal', function () {
        resetModal();
        $('#btn_save_task').unbind('click');
        $('#form_task').unbind('submit');
    });

});