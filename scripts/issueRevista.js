/*
created: Nick Avalos on Date: 05/11/2013
 */



window.addEvent('domready', function () {
    document.adminForm.boxchecked.value = 1;
    var carpetaImagenes = "components/com_gestorcore/imgs/";
    var selectListTopicos = $('#selectTopicosIssue');
    var buttonDelete_topico = $("#delete_topico");
    var buttonSimilares_topico = $("#similares_topico");
   
    var input_volumenIssue = $("#copy_volumenIssue");
    var input_numeroIssue = $("#copy_numeroIssue");
    var input_issnRevista = $("#copy_issnRevista");
    
    $('.jeditable_info').editable("index.php?option=com_gestorcore&controller=revista&task=put_editarIssue&format=raw", {
        indicator : "<img src='" + carpetaImagenes + "loading.gif'>",
        //loadurl   : "index.php?option=com_gestorcore&controller=congreso&task=getEditorialesCongreso_json&format=json",
        method    : "PUT",
        submit    : "OK",
        cancel    : "Cancel",
        tooltip   : "Doble click para editar...",
        event     : "dblclick",
        style     : "inherit",
        submitdata : function(value, settings) {
            return {revistaActual: input_issnRevista.val(), volumenActual: input_volumenIssue.val(), numeroActual: input_numeroIssue.val()};
        }
    });
    
    
    $('.jeditable_info_select').editable("index.php?option=com_gestorcore&controller=revista&task=put_editarIssue&format=raw", {
        indicator : "<img src='" + carpetaImagenes + "loading.gif'>",
        loadurl   : "index.php?option=com_gestorcore&controller=revista&task=getNomRevistas_ajax&format=ajax",
        type      : "select",
        method    : "PUT",
        submit    : "OK",
        cancel    : "Cancel",
        tooltip   : "Doble click para editar...",
        event     : "dblclick",
        style     : "inherit",
        submitdata : function(value, settings) {
            return {revistaActual: input_issnRevista.val(), volumenActual: input_volumenIssue.val(), numeroActual: input_numeroIssue.val()};
        }
    });
    
    $('.jeditable_info_date').editable("index.php?option=com_gestorcore&controller=revista&task=put_editarIssue&format=raw", {
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
            return {revistaActual: input_issnRevista.val(), volumenActual: input_volumenIssue.val(), numeroActual: input_numeroIssue.val()};
        }
    });
    
    selectListTopicos.change(function() {
        buttonDelete_topico.removeAttr('disabled');
        buttonDelete_topico.addClass('pointer');
        if ($("#selectTopicosIssue :selected").length === 1) {
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
    /**********************ELIMINAR TOPICO*****************************/
    buttonDelete_topico.click(function(event) {
        var result = confirm("¿Quieres eliminar los tópicos seleccionados?");
        if (result === true) {
            var datos = {
                issnRevista: input_issnRevista.val(),
                volumen: input_volumenIssue.val(),
                numero: input_numeroIssue.val(),
                topicosEliminar: selectListTopicos.val()
            };
            var url ='index.php?option=com_gestorcore&controller=revista' +
                 '&task=put_eliminarTopicosIssue&format=ajax';
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
    /*******similares que no pertenecen a la issue**********/
    buttonSimilares_topico.click(function(event) {
        var datos = {
            issnRevista: input_issnRevista.val(),
            volumen: input_volumenIssue.val(),
            numero: input_numeroIssue.val(),
            topicoSimilar: selectListTopicos.val().toString()
        };
        var url ='index.php?option=com_gestorcore&controller=revista' +
             '&task=get_topicosSimilaresIssue&format=ajax';
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
    
    /*********************************ADD TOPICO REVISTA*************************************/
    $("#add_topico_revista").click(function(event) {
        var datos = {
            issnRevista: input_issnRevista.val(),
            volumen: input_volumenIssue.val(),
            numero: input_numeroIssue.val(),
        };
        var url ='index.php?option=com_gestorcore&controller=revista' +
             '&task=get_TopicosRevista_noIssue&format=ajax';
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
    
    
    
    /*********************************ADD TOPICO GENERAL*************************************/
    
    var dialog_addTopicoEntidad = $( "#dialog-form_addTopicoEntidad" ),
        tips = $( "#dialog-form_addTopicoEntidad .validateTips" );
    
    $( "#add_topico" ).click(function() {
        //tips.text("Ingrese todos los datos de la nueva revista por favor.");
        //dialog_addCongreso.dialog('option', 'title', 'Create new question');
        
        var url ='index.php?option=com_gestorcore&controller=revista' +
                 '&task=getTopicosFiltradoIssue&format=ajax';
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
            {issnRevista: input_issnRevista.val(), volumen: input_volumenIssue.val(), numero: input_numeroIssue.val()}
        );
        
        //dialog_addTopicoEntidad.dialog( "open" );
    });
    
    /********************DIALOG*********************/
    
    var ejecutarNuevosTopicosIssue = function(datos, textStatus, request){
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
                    issnRevista: input_issnRevista.val(),
                    volumen: input_volumenIssue.val(),
                    numero: input_numeroIssue.val(),
                    nuevosTopicos: listaTopicos
                };
                var url ='index.php?option=com_gestorcore&controller=revista&task=postNuevosTopicosIssue&format=raw';
                ajaxConnection(url, 'json', 'POST', ejecutarNuevosTopicosIssue, ejecutarError, datos);
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