function reloadDetalle(id_maestro){

    $.ajax({
        url: appConfig.listDetalleUrl,
        type: "POST",
        data: {
            expandRowKey: id_maestro,
        },
        success: function(detalle){

            let element = $("tr")
                .find("div[data-key='" + id_maestro + "']");

            $(element).html(detalle);
        }
    });
}


function desvincularComponente(id_componente, id_maestro){
    $.ajax({
        url: appConfig.desvincularComponenteUrl,
        type: 'POST',
        dataType: 'json',
        data: {
            id_componente: id_componente,
            id_maestro: id_maestro
        },
        success: function(data){
            if(data.status === 'success'){
                $('#ajaxCrudModal').modal('hide');
                reloadDetalle(id_maestro);
            }else{
                $('#ajaxCrudModal .modal-title')
                    .html(data.title);
                $('#ajaxCrudModal .modal-body')
                    .html(data.content);
                $('#ajaxCrudModal').modal('show');
            }
        },
        error: function(xhr){
            $('#ajaxCrudModal .modal-title')
                .html('<p style="color:red">ERROR TECNICO</p>');
            $('#ajaxCrudModal .modal-body')
                .html('<div>Falló la petición AJAX</div>');
            $('#ajaxCrudModal').modal('show');
        }
    });
}


function addComponente(id_maestro){
    let keys = $('#cruddetalle-datatable')
        .yiiGridView('getSelectedRows');
    if(keys.length === 0){
        $('#error-no-seleccion')
            .text('Debes seleccionar al menos una opción.');
        return;
    }

    $.ajax({
        url: appConfig.addComponenteUrl,
        dataType: 'json',
        type: "POST",
        data: {
            keylist: keys,
            id_maestro: id_maestro
        },
        success: function(data){
            if(data.status === 'success'){
                $('#ajaxCrudModal').modal('hide');
                reloadDetalle(id_maestro);
            }else{
                $('#ajaxCrudModal .modal-title')
                    .html(data.title);
                $('#ajaxCrudModal .modal-body')
                    .html(data.content);
                $('#ajaxCrudModal').modal('show');
            }
        },
        error: function(xhr){
            $('#ajaxCrudModal .modal-title')
                .html('<p style="color:red">ERROR TECNICO</p>');
            $('#ajaxCrudModal .modal-body')
                .html('<div>Falló la petición AJAX</div>');
            $('#ajaxCrudModal').modal('show');
        }
    });
}

// codigo duplicado CORREGIR!!!
function reasigComponente(id_componente, id_maestro){
    let $radioChecked = $('input[name="kvradio"]:checked');
    if($radioChecked.length === 0){
        $('#error-no-seleccion')
            .text('Debes seleccionar al menos una opción.');

        return;
    }
    let keys = $radioChecked.val();
    let $row = $radioChecked.closest('tr');
    let dataKey = $row.data('key');
    $('#error-no-seleccion').text('');

    $.ajax({
        url: appConfig.reasigComponenteUrl,
        dataType: 'json',
        type: "POST",

        data: {
            keylist: keys,
            id_componente: id_componente,
            id_maestro: id_maestro
        },
        success: function(data){
            if(data.status === 'success'){
                $('#ajaxCrudModal').modal('hide');
                reloadDetalle(id_maestro);
            }else{
                $('#ajaxCrudModal .modal-title')
                    .html(data.title);
                $('#ajaxCrudModal .modal-body')
                    .html(data.content);
                $('#ajaxCrudModal').modal('show');
            }
        },
        error: function(xhr){
            $('#ajaxCrudModal .modal-title')
                .html('<p style="color:red">ERROR TECNICO</p>');
            $('#ajaxCrudModal .modal-body')
                .html('<div>Falló la petición AJAX</div>');
            $('#ajaxCrudModal').modal('show');
        }
    });
}
