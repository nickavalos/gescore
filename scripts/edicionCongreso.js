/*
created: Nick Avalos on Date: 05/11/2013
 */

window.addEvent('domready', function () {
    document.adminForm.boxchecked.value = 1;
    var carpetaImagenes = "components/com_gestorcore/imgs/";
    var selectListTopicos = $('#selectTopicosEdicion');
    var buttonDelete_topico = $("#delete_topico");
    var buttonSimilares_topico = $("#similares_topico");
   
    var inputOrdenEdicion = $("#copyOrden_edicion");
    var inputNomCongreso = $("#copyNomCongreso");
    
    document.adminForm.boxchecked.value = 1;
    
    $('.jeditable_info').editable("index.php?option=com_gestorcore&controller=congreso&task=put_editarEdicion&format=raw", {
        indicator : "<img src='" + carpetaImagenes + "loading.gif'>",
        //loadurl   : "index.php?option=com_gestorcore&controller=congreso&task=getEditorialesCongreso_json&format=json",
        method    : "PUT",
        submit    : "OK",
        cancel    : "Cancel",
        tooltip   : "Doble click para editar...",
        event     : "dblclick",
        style     : "inherit",
        submitdata : function(value, settings) {
            return {congresoActual: inputNomCongreso.val(), ordenActual: inputOrdenEdicion.val()};
        }
    });
    
    
    $('.jeditable_info_select').editable("index.php?option=com_gestorcore&controller=congreso&task=put_editarEdicion&format=raw", {
        indicator : "<img src='" + carpetaImagenes + "loading.gif'>",
        loadurl   : "index.php?option=com_gestorcore&controller=congreso&task=getNomCongresos_ajax&format=ajax",
        type      : "select",
        method    : "PUT",
        submit    : "OK",
        cancel    : "Cancel",
        tooltip   : "Doble click para editar...",
        event     : "dblclick",
        style     : "inherit",
        submitdata : function(value, settings) {
            return {congresoActual: inputNomCongreso.val(), ordenActual: inputOrdenEdicion.val()};
        }
    });
    
    $('.jeditable_info_date').editable("index.php?option=com_gestorcore&controller=congreso&task=put_editarEdicion&format=raw", {
        indicator : "<img src='" + carpetaImagenes + "loading.gif'>",
        type      : 'datepicker',
        datepicker: {
            changeMonth: true,
            changeYear: true,
            buttonImageOnly: false,
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            showAnim: 'slideDown'
        },
        method    : "PUT",
        submit    : "OK",
        cancel    : "Cancel",
        tooltip   : "Doble click para editar...",
        event     : "dblclick",
        //style     : "inherit",
        submitdata : function(value, settings) {
            return {congresoActual: inputNomCongreso.val(), ordenActual: inputOrdenEdicion.val()};
        }
    });
    /*
    $('.jeditable_info_date_ini').editable("index.php?option=com_gestorcore&controller=congreso&task=put_editarCongreso&format=raw", {
        indicator : "<img src='" + carpetaImagenes + "loading.gif'>",
        type      : 'datepicker',
        datepicker: {
            changeMonth: true,
            changeYear: true,
            buttonImageOnly: false,
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            showAnim: 'slideDown',
            onClose: function( selectedDate ) {
                $( ".jeditable_info_date_end" ).datepicker( "option", "minDate", selectedDate );
                $(this).keyup();
            }
        },
        method    : "PUT",
        submit    : "OK",
        cancel    : "Cancel",
        tooltip   : "Doble click para editar...",
        event     : "dblclick",
        //style     : "inherit",
        submitdata : function(value, settings) {
            return {congresoActual: inputNomCongreso.val()};
        }
    });
    
    $('.jeditable_info_date_end').editable("index.php?option=com_gestorcore&controller=congreso&task=put_editarCongreso&format=raw", {
        indicator : "<img src='" + carpetaImagenes + "loading.gif'>",
        type      : 'datepicker',
        datepicker: {
            changeMonth: true,
            changeYear: true,
            buttonImageOnly: false,
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            showAnim: 'slideDown',
            onClose: function( selectedDate ) {
                 $(this).keyup();
            }
        },
        method    : "PUT",
        submit    : "OK",
        cancel    : "Cancel",
        tooltip   : "Doble click para editar...",
        event     : "dblclick",
        //style     : "inherit",
        submitdata : function(value, settings) {
            return {congresoActual: inputNomCongreso.val()};
        }
    });
    */
    
    selectListTopicos.change(function() {
        buttonDelete_topico.removeAttr('disabled');
        buttonDelete_topico.addClass('pointer');
        if ($("#selectTopicosEdicion :selected").length === 1) {
            buttonSimilares_topico.removeAttr('disabled');
            buttonSimilares_topico.addClass('pointer');
        }
        else {
            buttonSimilares_topico.attr('disabled', true);
            buttonSimilares_topico.removeClass('pointer');
        }
    });
    
    
    
    
    /************************** TOPICOS ***********************************/
    var tableListTopicos = $("#table_listTopicos tbody"); 
    
    $('.checkall_topicos').on('click', function () {
        var checkBoxL = $(this).is(':checked');
        tableListTopicos.find('.checkBox_topicos').each( function(index){
            if(checkBoxL) {
                $(this).prop('checked', true);
            } else{
                $(this).prop('checked', false);
            }
        });
    });
    /**********************ELIMINAR TOPICO DEL CONGRESO*****************************/
    buttonDelete_topico.click(function(event) {
        var result = confirm("¿Quieres eliminar los tópicos seleccionados?");
        if (result === true) {
            var datos = {
                nomCongreso: inputNomCongreso.val(),
                orden: inputOrdenEdicion.val(),
                topicosEliminar: selectListTopicos.val()
            };
            var url ='index.php?option=com_gestorcore&controller=congreso' +
                 '&task=put_EliminarTopicosEdicion&format=ajax';
            ajaxConnection(
                url,
                'json',
                'GET',
                function(datos, textStatus, request){
                    selectListTopicos.find(":selected").remove();
                },
                function(xhr, textStatus, errorThrown){
                    alert(errorThrown); 
                },
                datos
            );
        }
    });
    /*******similares que no pertenecen a la edicion**********/
    buttonSimilares_topico.click(function(event) {
        var datos = {
            nomCongreso: inputNomCongreso.val(),
            orden: inputOrdenEdicion.val(),
            topicoSimilar: selectListTopicos.val().toString()
        };
        var url ='index.php?option=com_gestorcore&controller=congreso' +
             '&task=get_TopicosSimilaresEdicion&format=ajax';
        ajaxConnection(
            url,
            'json',
            'GET',
            function(datos, textStatus, request){
                tableListTopicos.children().remove();
                for (i = 0; i < datos.length; i++) {
                    var nuevaFila = $("#rowNuevoTopico table tbody").children().clone();
                    nuevaFila.addClass('row' + i%2);
                    nuevaFila.find('.checkBox_topicos').attr('value', datos[i]['nomTopicoSimilar']);
                    nuevaFila.append("<td>" + datos[i]['nomTopicoSimilar'] + "</td>");
                    nuevaFila.append("<td>" + datos[i]['descripcion'] + "</td>");
                    tableListTopicos.append(nuevaFila);
                }
                dialog_addTopicoEntidad.dialog( "open" );
            },
            function(xhr, textStatus, errorThrown){
                alert(errorThrown); 
            },
            datos
        );
        //dialog_addTopicoEntidad.dialog( "open" );
    });
    
    /*********************************ADD TOPICO CONGRESO*************************************/
    $("#add_topico_congreso").click(function(event) {
        var datos = {
            nomCongreso: inputNomCongreso.val(),
            orden: inputOrdenEdicion.val()
        };
        var url ='index.php?option=com_gestorcore&controller=congreso' +
             '&task=get_TopicosCongreso_noEdicion&format=ajax';
        ajaxConnection(
            url,
            'json',
            'GET',
            function(datos, textStatus, request){
                tableListTopicos.children().remove();
                for (i = 0; i < datos.length; i++) {
                    var nuevaFila = $("#rowNuevoTopico table tbody").children().clone();
                    nuevaFila.addClass('row' + i%2);
                    nuevaFila.find('.checkBox_topicos').attr('value', datos[i]['nomTopico']);
                    nuevaFila.append("<td>" + datos[i]['nomTopico'] + "</td>");
                    nuevaFila.append("<td>" + datos[i]['descripcion'] + "</td>");
                    tableListTopicos.append(nuevaFila);
                }
                dialog_addTopicoEntidad.dialog( "open" );
            },
            function(xhr, textStatus, errorThrown){
                alert(errorThrown); 
            },
            datos
        );
        //dialog_addTopicoEntidad.dialog( "open" );
    });
    
    
    
    /*********************************ADD TOPICO EDICION*************************************/
    
    var dialog_addTopicoEntidad = $( "#dialog-form_addTopicoEntidad" ),
        tips = $( "#dialog-form_addCongreso .validateTips" );
    
    $( "#add_topico" ).click(function() {
        tips.text("Ingrese todos los datos del nuevo congreso por favor.");
        //dialog_addCongreso.dialog('option', 'title', 'Create new question');
        
        var url ='index.php?option=com_gestorcore&controller=congreso' +
                 '&task=getTopicosFiltradoEdicion&format=ajax';
        ajaxConnection(
            url,
            'json',
            'GET',
            function(datos, textStatus, request){
                tableListTopicos.children().remove();
                for (i = 0; i < datos.length; i++) {
                    var nuevaFila = $("#rowNuevoTopico table tbody").children().clone();
                    nuevaFila.addClass('row' + i%2);
                    nuevaFila.find('.checkBox_topicos').attr('value', datos[i]['nombre']);
                    nuevaFila.append("<td>" + datos[i]['nombre'] + "</td>");
                    nuevaFila.append("<td>" + datos[i]['descripcion'] + "</td>");
                    tableListTopicos.append(nuevaFila);
                }
                dialog_addTopicoEntidad.dialog( "open" );
            },
            function(xhr, textStatus, errorThrown){
                alert(errorThrown); 
            },
            {nomCongreso: inputNomCongreso.val(), orden: inputOrdenEdicion.val()}
        );
        
        //dialog_addTopicoEntidad.dialog( "open" );
    });
    
    /********************DIALOG*********************/
    
    var ejecutarNuevoTopicosCongreso = function(datos, textStatus, request){
        for (i = 0; i < datos.length; i++) {
            var nuevoOpt = "<option value='" + datos[i] + "'>" + datos[i] + "</option>";
            selectListTopicos.append(nuevoOpt);
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
                tableListTopicos.find('.checkBox_topicos').each( function(index){
                    if($(this).is(':checked')) {
                        listaTopicos.push($(this).val());
                    }
                });
                var datos = {
                    nomCongreso: inputNomCongreso.val(),
                    orden: inputOrdenEdicion.val(),
                    nuevosTopicos: listaTopicos
                };
                var url ='index.php?option=com_gestorcore&controller=congreso&task=postNuevosTopicosEdicion&format=raw';
                ajaxConnection(url, 'json', 'POST', ejecutarNuevoTopicosCongreso, ejecutarError, datos);
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