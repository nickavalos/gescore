/*
created: Nick Avalos on Date: 05/11/2013
 */

/*
var crossSelect = function(target) {
    target.crossSelect({
        rows: 10,
        listWidth: 220,
        font: '11',
        horizontal: "scroll",
        dblclick: true,
        clickSelects: false,
        clicksAccumulate: true,
        select_txt: "< Seleccionar",
        remove_txt: "Quitar >",
        selectAll_txt: "< Selec. todo",
        removeAll_txt: "Quitar todo >",
        tituloIzq: "Tópicos para la issue",
        tituloDer: "Tópicos de la Revista"
    });
};*/

window.addEvent('domready', function () {
    
    //var checkBox_otrosTopicos = $("#checkOtrosTopicos");
    //var checkBox_nuevosTopicos = $("#checkNuevosTopicos");
    var checkBox_incluirDescripcion = $("#checkIncluirDescripcion");
    var button_addNuevoTopico = $("#buttonAddTopico");
    var button_deleteTopico = $("#delete_topico");
    //var button_similaresTopico = $("#similares_topico");
    
    var select_topicosSeleccionados = $("#listTopicosSeleccionados");
    var select_topicosSeleccionados_copy = $("#copy_listTopicosSeleccionados");
    //var select_revista = $("#selectorRevista");
    
    //var div_topicosFromRevista = $("#divTopicosFromRevista");
    
    
    var carpeta_imagenes = 'components/com_gestorcore/imgs/';
    var icon_lockEdicion = $("#iconlock");
    var icon_reloadCongreso = $("#reloadCongresoActual");
    //var select_edicion = $("#selectorEdicion");
    var select_congreso = $("#selectorCongreso");
    var select_edicion = $("#selectorEdicion");
    var input_copyCongreso = $('#congresoActual');
    var table_nuevosTopicos = $("#tableNuevosTopicos tbody");
    
    var button_addValoracion = $("#buttonAddValoracion");
    var button_addCongreso = $( "#add_congreso" );
    
    var dialog_addCongreso = $( "#dialog-form_addCongreso" );
    
    var task_saveNuevaEdicion = 'saveNuevaEdicion';
    var task_saveNuevaEdicionCongreso = 'saveNuevaEdicionCongreso';
    var task_saveEditEdicion_congreso = 'saveEdicion_showCongreso';
    var task_saveEditEdicion_edicion = 'saveEdicion_showEdicion';
    var task_cancelarNuevaEdicion = 'cancelarNuevaEdicion';
    
    var noCongresSelected = '-1';
    /*******************Listeners*********************/
    icon_reloadCongreso.click(function(event) {
        $("#selectorCongreso option[value='" + input_copyCongreso.val() + "']").attr('selected', 'selected');
        select_congreso.trigger("chosen:updated").change();
    });
    
    button_addCongreso.click(function() {
        dialog_addCongreso.dialog( "open" );
    });
    
    button_deleteTopico.click(function() { 
        //select_topicosSeleccionados.find(":selected").remove();
        select_topicosSeleccionados.find(":selected").each(function() {
            select_topicosSeleccionados_copy.find("[value='" + $(this).val() + "']").first().remove();
            $(this).remove();
        });
    });
    
    /*checkBox_nuevosTopicos.click(function() {
        $("#tableNuevosTopicos").slideToggle();
        button_addNuevoTopico.slideToggle();
    });*/
    
    button_addNuevoTopico.click(function() {
        var nuevaFila = $("#htmlOculto #filaTableNuevosTopicos tbody").clone().children();
        table_nuevosTopicos.append(nuevaFila);
    });
    
    button_addValoracion.click(function() {
        var nuevaFila = $("#htmlOculto #filaTableEntidadValoracion tbody").clone().children();
        nuevaFila.children("td").children(".selectorEntidadCalidad").chosen();
        nuevaFila.children("td").children(".chosen-container").css({ "width": "75%" });
        
        $("#tableValoraciones tbody").append(nuevaFila);
    });
    
    /**********************************CHOSEN****************************************/
    $(".chosen").chosen();
    
    /*************************************BUSCADOR DE TOPICO******************************************************/
    $.widget( "custom.catcomplete", $.ui.autocomplete, {
        _renderMenu: function( ul, items ) {
            var that = this, currentCategory = "";
            $.each( items, function( index, item ) {
                if ( item.category !== currentCategory ) {
                    ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
                    currentCategory = item.category;
                }
                that._renderItemData( ul, item );
            });
        }
    });
    
    function log( topico ) {
        //$( "<div>" ).text( message ).prependTo( "#log" );
        //$( "#log" ).scrollTop( 0 );
        var newOption = "<option value='" + topico + "'>" + topico + '</option>';
        select_topicosSeleccionados.append(newOption);
        select_topicosSeleccionados_copy.append("<option value='" + topico + "' selected>" + topico + '</option>');
    }
 
    $( "#inputAddTopico" ).catcomplete({
        source: function( request, response ) {
            $.ajax({
                url: "index.php?option=com_gestorcore&controller=revista&task=getNomTopicos_jsonp&format=ajax",
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    incluirDescripcion: checkBox_incluirDescripcion.is(':checked')
                },
                success: function( data ) {
                    response( $.map( data, function( item ) {
                        return {
                            label: item.nombre,
                            category: item.category,
                            nomTopico: item.nomTopico
                        };
                    }));
                }
            });
        },
        minLength: 2,
        select: function( event, ui ) {
            var etiq = ui.item.category === 'Nombre'?ui.item.label:ui.item.nomTopico; 
            //log( ui.item ? "Selected: " + etiq : "Nothing selected, input was " + this.value);
            log(etiq);
        },
        open: function() {
            $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
            $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
            $(this).val('');
        }
    });
   

    
    /******************FORM DIALOG: ADD CONGRESO***********************/
    $("#dialog-form_addCongreso div").load('index.php?option=com_gestorcore&view=congresos&layout=default_nuevoCongreso&tmpl=component #content_addCongreso', function() {
    
    /****************************** Dialog: Nuevo Congreso *****************************/
    
    var nomEntidad = $( "#nomEntidad" ),
        acronimoEntidad = $( "#acronimoEntidad" ),
        linkEntidad = $( "#linkEntidad" ),
        periodicidadEntidad = $( "#periodicidadEntidad" ),
        allFields_dialog_entidad = $( [] ).add( nomEntidad ).add( acronimoEntidad ).add( linkEntidad ).add( periodicidadEntidad ),
        tips = $( "#dialog-form_addCongreso .validateTips" );
    
    var nomEditorial = $( "#nomEditorial" ),
        siglasEditorial = $( "#siglasEditorial" ),
        linkEditorial = $( "#linkEditorial" ),
        allFields_dialog_editorial = $( [] ).add( nomEditorial ).add( siglasEditorial ).add( linkEditorial );
    
    var editorialesEntidad = $("#selectEditorialesEntidad");    
    
    $('#tableEscogerEditorial input[type=radio][name=radioEntidadPublicadora]').change(function() {
        if (this.value === 'editorialExistente') {
            $("#existenteEditorialEntidad").removeAttr("hidden");
            $("#nuevaEditorialEntidad").attr("hidden", true);
        }
        else if (this.value === 'nuevaEditorial') {
            $("#existenteEditorialEntidad").attr("hidden", true);
            $("#nuevaEditorialEntidad").removeAttr("hidden");
        }
    });
    
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
            //object.removeClass( "dialog-ok" );
            //object.addClass( "dialog-error" );
            object.addClass( "ui-state-error" );
            //updateTips( "Length of " + n + " must be between " + min + " and " + max + "." );
            updateTips( "Required field: " + n + " no puede ser vacio." );
            return false;
        }
        //object.addClass( "dialog-ok" );
        //object.removeClass( "dialog-error" );
        //object.addClass( "dialog-error" );
        return true;
    };
    var ejecutarNuevoCongreso = function(datos, textStatus, request){
        //alert(datos['test']);
        if (typeof(datos['error']) !== 'undefined') { //Error
            alert(datos['error']);
        }
        else {
            var nuevaOpcion = "<option value='" + datos['nuevaEntidad'] + "'>" + datos['nuevaEntidad'] + '</option>';
            select_congreso.append(nuevaOpcion);
            $("#selectorCongreso option[value='" + datos['nuevaEntidad'] + "']").attr('selected', 'selected'); //TODO:selector
            select_congreso.trigger("chosen:updated").change();
            alert("Congreso '" + datos['nuevaEntidad'] + "' añadido correctamente");
            //desbloqueamos
            if (select_edicion.attr("readonly")) {
                icon_lockEdicion.attr("src", carpeta_imagenes + 'iconUnlock.png');
                select_edicion.removeAttr("readonly").removeClass("sololectura");
            }
        }
    };
    var ejecutarError = function(xhr, textStatus, errorThrown){
        alert(errorThrown); 
    };
    
    dialog_addCongreso.dialog({
        autoOpen: false,
        height: 450,
        width: 550,
        modal: true,
        open: function() {
            tips.text("Ingrese todos los datos del nuevo congreso por favor.");
            //dialog_addCongreso.dialog('option', 'title', 'Create new question');
            //dialog_addCongreso.data("typeDialog", "add");

            editorialesEntidad.children().remove();

            var url ='index.php?option=com_gestorcore&controller=congreso' +
                     '&task=getEditoriales_ajax&format=ajax';
            ajaxConnection(
                url,
                'json',
                'GET',
                function(datos, textStatus, request){
                    //dialog_addCongreso.data("editoriales", datos);
                    for (i = 0; i < datos.length; i++) {
                        var string = "<option value='" + datos[i]['nombre'] + "'>" + datos[i]['nombre'] + '</option>';
                        if (i === 0) {
                            string = "<option value='" + datos[i]['nombre'] + "' selected>" + datos[i]['nombre'] + '</option>';
                        }
                        editorialesEntidad.append(string);
                    }
                },
                function(xhr, textStatus, errorThrown){
                    alert(errorThrown); 
                }
                //parametros
            );
        },
        buttons: {
            "Añadir Congreso": function(event, ui) {
                var bValid = true;
                allFields_dialog_entidad.removeClass( "ui-state-error" );
                allFields_dialog_editorial.removeClass( "ui-state-error" );
                bValid = bValid && checkLength( nomEntidad, "Nombre de la entidad", 1, -1 );
                if ( $('#tableEscogerEditorial input[type=radio]:checked').val() === "nuevaEditorial") {
                    bValid = bValid && checkLength( nomEditorial, "Nombre de la Editorial", 1, -1 );
                }
                if ( bValid ) {
                    var datos = {
                        nomEntidad: nomEntidad.val(),
                        acronimoEntidad: acronimoEntidad.val(),
                        linkEntidad: linkEntidad.val(),
                        periodicidadEntidad: periodicidadEntidad.val()
                    };
                    if ($('#tableEscogerEditorial input[type=radio]:checked').val() === "nuevaEditorial") {
                        datos.tipoEditorial = "nuevaEditorial";
                        datos.nomEditorial = nomEditorial.val();
                        datos.siglasEditorial = siglasEditorial.val();
                        datos.linkEditorial = linkEditorial.val();
                    }
                    else {
                        datos.tipoEditorial = "editorialExistente";
                        datos.nomEditorial = editorialesEntidad.val();
                    }
                    var url ='index.php?option=com_gestorcore&controller=congreso' + 
                             '&task=postNuevoCongreso&format=raw';
                    ajaxConnection(url, 'json', 'POST', ejecutarNuevoCongreso, ejecutarError, datos);
                    $( this ).dialog( "close" );    
                }
            },
            Cancel: function() {
                $( this ).dialog( "close" );
            }
        },
        close: function() {
            allFields_dialog_entidad.val( "" ).removeClass( "ui-state-error" );
            allFields_dialog_editorial.val( "" ).removeClass( "ui-state-error" );
        }
    });
    });
    
    Joomla.submitbutton = function(task) { // captura del evento de submit
        var save = (task === task_saveNuevaEdicion) || (task === task_saveNuevaEdicionCongreso);
        save = save || (task === task_saveEditEdicion_congreso) || (task === task_saveEditEdicion_edicion);
        if (save) { 
            var bValid = true;
            bValid = bValid && (select_congreso.val() !== noCongresSelected);
            bValid = bValid && (select_edicion.val() !== '');
            if (bValid) Joomla.submitform(task, document.getElementById('form_gestionEdicion'));
            else alert('El congreso y/o la edición no pueden ser vacios');
        }
        else if (task === task_cancelarNuevaEdicion) { //back
            //Joomla.submitbuttonevent.preventDefault();
            //window.history.go(-1);
            window.location.href = 'index.php?option=com_gestorcore';
        }else { //cancelar, modo desde congreso
            Joomla.submitform(task, document.getElementById('form_gestionEdicion'));
        }
    };
    
});