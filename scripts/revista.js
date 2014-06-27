/*
created: Nick Avalos on Date: 05/11/2013
 */

window.addEvent('domready', function () {
    
    var carpetaImagenes = "components/com_gestorcore/imgs/";
    var topicosRevista = $('#selectTopicosRevista');
    var buttonDelete_topicoRevista = $("#delete_topicoRevista");
    var buttonSimilares_topicosRevista = $("#similares_topicoRevista");
    var buttonAdd_topicoRevista = $("#add_topicoRevista");
    var inputIssnRevista = $("#copyIssnRevista");
    
    $('.jeditable_info').editable("index.php?option=com_gestorcore&controller=revista&task=put_editarRevista&format=raw", {
        indicator : "<img src='" + carpetaImagenes + "loading.gif'>",
        //loadurl   : "index.php?option=com_gestorcore&controller=congreso&task=getEditorialesCongreso_json&format=json",
        method    : "PUT",
        submit    : "OK",
        cancel    : "Cancel",
        tooltip   : "Doble click para editar...",
        event     : "dblclick",
        style     : "inherit",
        submitdata : function(value, settings) {
            return {revistaActual: inputIssnRevista.val()};
        }
    });
    
    
    $('.jeditable_info_select').editable("index.php?option=com_gestorcore&controller=revista&task=put_editarRevista&format=raw", {
        indicator : "<img src='" + carpetaImagenes + "loading.gif'>",
        loadurl   : "index.php?option=com_gestorcore&controller=congreso&task=getNomEditoriales_ajax&format=ajax",
        type      : "select",
        method    : "PUT",
        submit    : "OK",
        cancel    : "Cancel",
        tooltip   : "Doble click para editar...",
        event     : "dblclick",
        style     : "inherit",
        submitdata : function(value, settings) {
            return {revistaActual: inputIssnRevista.val()};
        }
    });
    
    topicosRevista.change(function() {
        buttonDelete_topicoRevista.removeAttr('disabled');
        buttonDelete_topicoRevista.addClass('pointer');
        if ($("#selectTopicosRevista :selected").length === 1) {
            buttonSimilares_topicosRevista.removeAttr('disabled');
            buttonSimilares_topicosRevista.addClass('pointer');
        }
        else {
            buttonSimilares_topicosRevista.attr('disabled', true);
            buttonSimilares_topicosRevista.removeClass('pointer');
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
    /**********************ELIMINAR TOPICO DE LA REVISTA*****************************/
    buttonDelete_topicoRevista.click(function(event) {
        var result = confirm("¿Quieres eliminar los tópicos seleccionados?");
        if (result === true) {
            var datos = {
                issnRevista: inputIssnRevista.val(),
                topicosEliminar: topicosRevista.val()
            };
            var url ='index.php?option=com_gestorcore&controller=revista' +
                 '&task=put_eliminarTopicosRevista&format=ajax';
            ajaxConnection(
                url,
                'json',
                'GET',
                function(datos, textStatus, request){
                    topicosRevista.find(":selected").remove();
                },
                function(xhr, textStatus, errorThrown){
                    alert(errorThrown); 
                },
                datos
            );
        }
    });
    /*******similares que no pertenecen a la revista**********/
    buttonSimilares_topicosRevista.click(function(event) {
        var datos = {
            issnRevista: inputIssnRevista.val(),
            topicoSimilar: topicosRevista.val().toString()
        };
        var url ='index.php?option=com_gestorcore&controller=revista' +
             '&task=get_topicosSimilaresRevista&format=ajax';
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
    
    var dialog_addTopicoEntidad = $( "#dialog-form_addTopicoEntidad" );
    
    buttonAdd_topicoRevista.click(function() {
        //tips.text("Por favor seleccione los tópicos que quiere añadir a la revista.");
        //dialog_addCongreso.dialog('option', 'title', 'Create new question');
        
        var url ='index.php?option=com_gestorcore&controller=revista' +
                 '&task=getTopicosFiltrado_revista&format=ajax';
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
            {issnRevista: inputIssnRevista.val()}
        );
        
        //dialog_addTopicoEntidad.dialog( "open" );
    });
    
    
    /*********************************POPUP*******************************************/
    var ejecutarNuevoTopicosRevista = function(datos, textStatus, request){
        for (i = 0; i < datos.length; i++) {
            var nuevoOpt = "<option value='" + datos[i] + "'>" + datos[i] + "</option>";
            topicosRevista.append(nuevoOpt);
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
                    issnRevista: inputIssnRevista.val(),
                    nuevosTopicos: listaTopicos
                };
                var url ='index.php?option=com_gestorcore&controller=revista&task=postNuevosTopicosRevista&format=raw';
                ajaxConnection(url, 'json', 'POST', ejecutarNuevoTopicosRevista, ejecutarError, datos);
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
    
    
    /**********************************************DIALOG NUEVA EDITORIAL****************************************************/
    var dialog_nuevaEditorial = $("#dialog_nuevaEditorial");
    var nomEditorial = $( "#nomEditorial" ),
        siglasEditorial = $( "#siglasEditorial" ),
        linkEditorial = $( "#linkEditorial" ),
        allFields_dialog_editorial = $( [] ).add( nomEditorial ).add( siglasEditorial ).add( linkEditorial ),
        tips = dialog_nuevaEditorial.find('.validateTips' );
    
    var button_nuevaEditorial = $("#buttonNuevaEditorial");
    
    var updateTips = function ( t ) {
        tips
        .text( t )
        .addClass( "ui-state-highlight" );
        setTimeout(function() {
        tips.removeClass( "ui-state-highlight", 1500 );
        }, 500 );
    };
    var checkLength = function ( object, n, min, max ) {
        if ( object.val().length < min ) {
            object.addClass( "ui-state-error" );
            updateTips( "Required field: " + n + " no puede ser vacio." );
            return false;
        }
        return true;
    };
    
    dialog_nuevaEditorial.dialog({
        autoOpen: false,
        height: 380,
        width: 400,
        modal: true,
        buttons: {
            "Nueva Editorial": function(event, ui) {
                var bValid = true;
                allFields_dialog_editorial.removeClass( "ui-state-error" );
                bValid = bValid && checkLength( nomEditorial, "Nombre", 1, -1 );
                if (bValid) {
                    var datos = {
                        nombre: nomEditorial.val(),
                        siglas: siglasEditorial.val(),
                        link: linkEditorial.val()
                    };
                    var url ='index.php?option=com_gestorcore&controller=editorial&task=postNuevaEditorial&format=raw';
                    ajaxConnection(url, 
                                    'json', 
                                    'POST', 
                                    function(datos) {
                                        if (datos['error']) {
                                            alert(datos['error']);
                                        }else {
                                            alert("Editorial añadido correctamente");
                                        }
                                    }, 
                                    function(xhr, textStatus, errorThrown){
                                        alert(errorThrown); 
                                    }, 
                                    datos);
                    $( this ).dialog( "close" );
                }
            },
            Cancel: function() {
                $( this ).dialog( "close" );
            }
        },
        close: function() {
        }
    });
    
    button_nuevaEditorial.click(function() {
        dialog_nuevaEditorial.dialog('open');
    });
    
    
});