/*
created: Nick Avalos on Date: 05/11/2013
 */

window.addEvent('domready', function () {
    
   
    var carpetaImagenes = "components/com_gestorcore/imgs/";
    var select_listTopicosSimilares = $('#lista_topicosSimilares');
    var button_addTopico = $('#add_topicoSimilar');
    var button_deleteTopico = $("#delete_topicoSimilar");
    var table_listTopicos_popup = $("#table_listTopicos tbody"); 
    var label_nomTopico = $("#nomTopico");
    
    $('.jeditable_textArea_topico').editable("index.php?option=com_gestorcore&controller=topico&task=put_editTopico&format=raw", {
        indicator : "<img src='" + carpetaImagenes + "loading.gif'>",
        type      : "textarea",
        rows      : "3",
        cols      : "38",
        method    : "PUT",
        submit    : "OK",
        cancel    : "Cancel",
        tooltip   : "Doble click para editar...",
        event     : "dblclick",
        style     : "inherit",
        submitdata : function(value, settings) {
            return {nomTopico_copy: $("#nomTopico_copy").val()};
        }
    });
    
    
    select_listTopicosSimilares.change(function() {
        enableButton(button_deleteTopico);
    });
    
    
    /************************** TOPICOS ***********************************/
    
    
    $('.checkall_topicos').on('click', function () {
        var checkBoxL = $(this).is(':checked');
        table_listTopicos_popup.find('.checkBox_topicos').each( function(index){
            if(checkBoxL) {
                $(this).prop('checked', true);
            } else{
                $(this).prop('checked', false);
            }
        });
    });
    /**********************ELIMINAR TOPICO *****************************/
    button_deleteTopico.click(function(event) {
        var result = confirm("¿Quieres eliminar los tópicos seleccionados?");
        if (result) {
            var datos = {
                nomTopico: label_nomTopico.text(),
                topicosEliminar: select_listTopicosSimilares.val()
            };
            var url ='index.php?option=com_gestorcore&controller=topico' +
                 '&task=put_eliminarTopicos&format=ajax';
            ajaxConnection(
                url,
                'json',
                'GET',
                function(datos, textStatus, request){
                    select_listTopicosSimilares.find(":selected").remove();
                },
                function(xhr, textStatus, errorThrown){
                    alert(errorThrown); 
                },
                datos
            );
            disableButton(button_deleteTopico);
        }
    });
    
    
    
    /*********************************ADD TOPICO SIMILAR *************************************/
    
    var dialog_addTopicoEntidad = $( "#dialog-form_addTopicoEntidad" ),
        tips = $( "#dialog-form_addCongreso .validateTips" );
    
    button_addTopico.click(function() { //optenemos topicos
        //tips.text("Ingrese todos los datos del nuevo congreso por favor.");
        //dialog_addCongreso.dialog('option', 'title', 'Create new question');
        
        var url ='index.php?option=com_gestorcore&controller=topico' +
                 '&task=get_topicosFiltrados_ajax&format=ajax';
        ajaxConnection(
            url,
            'json',
            'GET',
            function(datos, textStatus, request){
                table_listTopicos_popup.children().remove();
                for (i = 0; i < datos.length; i++) {
                    var nuevaFila = $("#rowNuevoTopico table tbody").children().clone();
                    nuevaFila.addClass('row' + i%2);
                    nuevaFila.find('.checkBox_topicos').attr('value', datos[i]['nombre']);
                    nuevaFila.append("<td>" + datos[i]['nombre'] + "</td>");
                    nuevaFila.append("<td>" + datos[i]['descripcion'] + "</td>");
                    table_listTopicos_popup.append(nuevaFila);
                }
                dialog_addTopicoEntidad.dialog( "open" );
            },
            function(xhr, textStatus, errorThrown){
                alert(errorThrown); 
            },
            {nomTopico: label_nomTopico.text()}
        );
        
        //dialog_addTopicoEntidad.dialog( "open" );
    });
    
    var ejecutarNuevosTopicosSimilares = function(datos, textStatus, request){
        for (i = 0; i < datos.length; i++) {
            var nuevoOpt = "<option value='" + datos[i] + "'>" + datos[i] + "</option>";
            select_listTopicosSimilares.append(nuevoOpt);
        }
    };
    
    var ejecutarError = function(xhr, textStatus, errorThrown){
        alert(errorThrown); 
    };
    
    dialog_addTopicoEntidad.dialog({
        autoOpen: false,
        height: 450,
        width: 600,
        modal: true,
        buttons: {
            "Añadir topicos": function(event, ui) {
                var listaTopicos = [];
                table_listTopicos_popup.find('.checkBox_topicos').each( function(index){
                    if($(this).is(':checked')) {
                        listaTopicos.push($(this).val());
                    }
                });
                var datos = {
                    nomTopico: label_nomTopico.text(),
                    nuevosTopicos: listaTopicos
                };
                var url ='index.php?option=com_gestorcore&controller=topico&task=post_nuevosTopicosSimilares&format=raw';
                ajaxConnection(url, 'json', 'POST', ejecutarNuevosTopicosSimilares, ejecutarError, datos);
                $( this ).dialog( "close" );    
            },
            Cancel: function() {
                $( this ).dialog( "close" );
            }
        },
        close: function() {
            $(".checkall_topicos").prop('checked', false);
        }
    });
    
    
    
    
});