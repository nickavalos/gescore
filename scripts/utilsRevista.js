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
            tituloIzq: "Topicos para la issue",
            tituloDer: "Topicos de la Revista"
        });
    };*/

window.addEvent('domready', function () {
    
    var selector_numeroIssue = $("#selectorNumeroIssue");
    //var checkBox_otrosTopicos = $("#checkOtrosTopicos");
    //var checkBox_nuevosTopicos = $("#checkNuevosTopicos");
    var checkBox_incluirDescripcion = $("#checkIncluirDescripcion");
    var checkBox_specialIssue  = $("#checkSpecialIssue");
    var button_addTopico = $("#buttonAddTopico");
    var button_deleteTopico = $("#delete_topico");
    //var button_similaresTopico = $("#similares_topico");
    var button_addValoracion = $("#buttonAddValoracion");
    var button_addRevista = $("#add_revista");
    
    var select_topicosSeleccionados = $("#listTopicosSeleccionados");
    var select_topicosSeleccionados_copy = $("#copy_listTopicosSeleccionados");
    var select_revista = $("#selectorRevista");
    var select_volumenesRevista = $("#selectorVolumenesRevista");
    var select_numeroIssue = $("#selectorNumeroIssue");
    
    var input_copyIssn = $("#issnRevistaActual");
    //var input_copyVolumen = $("#volumenIssueActual");
    //var input_copyNumero = $("#numeroIssueActual");
    var input_nuevoVolumen = $("#nuevoVolumen");
    var input_temaPrincipal = $("#temaPrincipal");
    //var span_ultimoNumeroVolumenIssue = $("#ultimoNumeroVolumen");
    var icon_lockNumeroIssue = $("#iconlock");
    
    //var div_topicosFromRevista = $("#divTopicosFromRevista");
    var div_edicionSimple = $("#divEdicionSimple");
    var div_edicionEspecial = $("#divEdicionEspecial");
    var div_numeroIssue = $("#divNumeroIssue");
    
    
    var idSpecialIssue = 'specialIssue';
    var idNuevoVolumen = '-1';
    var noRevistaSelected = '-1';
    
    var task_saveNuevaIssue = 'saveNuevaIssue';
    var task_saveNuevaIssueRevista = 'saveNuevaIssueRevista';
    var task_saveEditIssue_revista = 'saveIssue_showRevista';
    var task_saveEditIssue_issue = 'saveIssue_showIssue';
    var task_cancelarNuevaIssue = 'cancelarNuevaIssue';
    
    
    /*******************Listeners*********************/
    icon_lockNumeroIssue.click(function(event) {
        if (selector_numeroIssue.attr("readonly")) {
            $(this).attr("src", "components/com_gestorcore/imgs/iconUnlock.png");
            selector_numeroIssue.removeAttr("readonly").removeClass("sololectura");
        }
        else {
            $(this).attr("src", "components/com_gestorcore/imgs/iconLock.png");
            selector_numeroIssue.attr("readonly", true).addClass("sololectura");
        }
    });
    
    $("#iconlock_revista").click(function(event) {
        $("#selectorRevista option[value='" + input_copyIssn.val() + "']").attr('selected', 'selected');
        //$("#selectorVolumenRevista option[value='" + input_copyVolumen.val() + "']").attr('selected', 'selected');
        //$("#selectorNumeroIssue option[value='" + input_copyNumero.val() + "']").attr('selected', 'selected');
        $('#selectorRevista').trigger("chosen:updated");
        $('#selectorRevista').change();
    });
    
    
    /*checkBox_nuevosTopicos.click(function() {
        $("#tableNuevosTopicos").slideToggle();
        button_addTopico.slideToggle();
    });*/
    
    button_addTopico.click(function() {
        var nuevaFila = $("#htmlOculto #filaTableNuevosTopicos tbody").clone().children();
        $("#tableNuevosTopicos tbody").append(nuevaFila);
    });
    
    button_deleteTopico.click(function() { //warrada pero mola
        //select_topicosSeleccionados.find(":selected").remove();
        select_topicosSeleccionados.find(":selected").each(function() {
            select_topicosSeleccionados_copy.find("[value='" + $(this).val() + "']").first().remove();
            $(this).remove();
        });
    });
    
    button_addValoracion.click(function() {
        var nuevaFila = $("#htmlOculto #filaTableEntidadValoracion tbody").clone().children();
        nuevaFila.children("td").children(".selectorEntidadCalidad").chosen();
        nuevaFila.children("td").children(".chosen-container").css({ "width": "75%" });
        
        $("#tableValoraciones tbody").append(nuevaFila)
    });
    
    /*checkBox_specialIssue.click(function() {
        div_edicionSimple.slideToggle();
        if (checkBox_specialIssue.is(":checked")) {
            icon_lockNumeroIssue.attr('hidden', true);
            if (!selector_numeroIssue.attr("readonly")) icon_lockNumeroIssue.click();
        } else {
            icon_lockNumeroIssue.removeAttr('hidden');
        }
        select_volumenesRevista.change();
    });*/
    
    $('#tableEscogerTipoEdicion input[type=radio]').change(function() {
        if (this.value === 'edicionSimple') {
            div_edicionSimple.removeAttr("hidden");
            div_numeroIssue.removeAttr("hidden");
            div_edicionEspecial.attr("hidden", true);
        }
        else if (this.value === 'edicionEspecial') {
            div_edicionSimple.attr("hidden", true);
            div_numeroIssue.attr("hidden", true);
            div_edicionEspecial.removeAttr("hidden");
        }
    });
    
    /*^*************************************************************************/
    $(".chosen").chosen();
    
    select_volumenesRevista.change(function() {
        if ($(this).val() === idNuevoVolumen) {
            input_nuevoVolumen.removeClass('sololectura').removeAttr('disabled');
        } else {
            input_nuevoVolumen.addClass('sololectura').attr('disabled', true);   
        }
        
    });
    
    
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
    
    $(".topico_complete").catcomplete({
        source: function( request, response ) {
            $.ajax({
                url: "index.php?option=com_gestorcore&controller=revista&task=getNomTopicos_jsonp&format=ajax",
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    incluirDescripcion: false
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
        minLength: 1,
        select: function( event, ui ) {
            var etiq = ui.item.category === 'Nombre'?ui.item.label:ui.item.nomTopico; 
            //log( ui.item ? "Selected: " + etiq : "Nothing selected, input was " + this.value);
            $(this).val(etiq);
        },
        open: function() {
            $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
            $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
            
        }
    });
    
    /******************FORM DIALOG: ADD REVISTA***********************/
    $("#dialog-form_addRevista div").load('index.php?option=com_gestorcore&view=revistas&layout=default_nuevaRevista&tmpl=component #content_addRevista', function() {
    
    /****************************** Dialog: Nueva Revista *****************************/
    var dialog_addRevista = $( "#dialog-form_addRevista" ),
        issnEntidad = $("#issnEntidad"),
        nomEntidad = $( "#nomEntidad" ),
        acronimoEntidad = $( "#acronimoEntidad" ),
        linkEntidad = $( "#linkEntidad" ),
        periodicidadEntidad = $( "#periodicidadEntidad" ),
        allFields_dialog_entidad = $( [] ).add(issnEntidad).add( nomEntidad ).add( acronimoEntidad ).add( linkEntidad ).add( periodicidadEntidad ),
        tips = $( "#dialog-form_addRevista .validateTips" );
    
    var nomEditorial = $( "#nomEditorial" ),
        siglasEditorial = $( "#siglasEditorial" ),
        linkEditorial = $( "#linkEditorial" ),
        allFields_dialog_editorial = $( [] ).add( nomEditorial ).add( siglasEditorial ).add( linkEditorial );
    
    var editorialesEntidad = $("#selectEditorialesEntidad");    
    
    var radio_existenteEditorialEntidad = $("#existenteEditorialEntidad");
    var radio_nuevaEditorialEntidad = $("#nuevaEditorialEntidad");
    
    button_addRevista.click(function() {
        //tips.text("Ingrese todos los datos de la nueva revista por favor.");
        
        var url ='index.php?option=com_gestorcore&controller=congreso' +
                 '&task=getEditoriales_ajax&format=ajax';
         
        editorialesEntidad.children().remove(); 
        ajaxConnection(
            url,
            'json',
            'GET',
            function(datos, textStatus, request){
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
        dialog_addRevista.dialog( "open" );
    });
    
    $('#tableEscogerEditorial input[type=radio][name=radioEntidadPublicadora]').change(function() {
        if (this.value === 'editorialExistente') {
            radio_existenteEditorialEntidad.removeAttr("hidden");
            radio_nuevaEditorialEntidad.attr("hidden", true);
        }
        else if (this.value === 'nuevaEditorial') {
            radio_existenteEditorialEntidad.attr("hidden", true);
            radio_nuevaEditorialEntidad.removeAttr("hidden");
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
    var ejecutarNuevoRevista = function(datos, textStatus, request){
        //alert(datos['test']);
        if (typeof(datos['error']) !== 'undefined') { //Error
            alert(datos['error']);
        }
        else {
            var nuevaOpcion = "<option value='" + datos['nuevaEntidad'] + "'>" + datos['nuevaEntidad'] + '</option>';
            $('#selectorRevista').append(nuevaOpcion);
            $("#selectorRevista option[value='" + datos['nuevaEntidad'] + "']").attr('selected', 'selected');
            $('#selectorRevista').trigger("chosen:updated").change();
            alert("Revista '" + datos['nuevaEntidad'] + "' añadida correctamente");
        }
    };
    var ejecutarError = function(xhr, textStatus, errorThrown){
        alert(errorThrown); 
    };
    
    dialog_addRevista.dialog({
        autoOpen: false,
        height: 505,
        width: 550,
        modal: true,
        buttons: {
            "Añadir Revista": function(event, ui) {
                var bValid = true;
                allFields_dialog_entidad.removeClass( "ui-state-error" );
                allFields_dialog_editorial.removeClass( "ui-state-error" );
                bValid = bValid && checkLength( issnEntidad, "Issn de la revista", 1, -1 );
                if ( $('#tableEscogerEditorial input[type=radio]:checked').val() === "nuevaEditorial") {
                    bValid = bValid && checkLength( nomEditorial, "Nombre de la Editorial", 1, -1 );
                }
                if ( bValid ) {
                    var datos = {
                        issnEntidad: issnEntidad.val(),
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
                    var url ='index.php?option=com_gestorcore&controller=revista' + 
                             '&task=postNuevaRevista&format=raw';
                    ajaxConnection(url, 'json', 'POST', ejecutarNuevoRevista, ejecutarError, datos);
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
        var save = (task === task_saveNuevaIssue) || (task === task_saveNuevaIssueRevista);
        save = save || (task === task_saveEditIssue_revista) || (task === task_saveEditIssue_issue);
        if (save) { 
            var bValid = true;
            bValid = bValid && (select_revista.val() !== noRevistaSelected);
            if (!bValid) { alert('Tienes que seleccionar una revista'); return;}
            
            if ($('#tableEscogerTipoEdicion input[type=radio]:checked').val() === "edicionSimple") {
                bValid = bValid && (select_volumenesRevista.val() !== idNuevoVolumen || input_nuevoVolumen.val() !== '');
                if (!bValid) { alert('Tienes que indicar un volumen o crear uno nuevo'); return;}
                
                bValid = bValid && (select_numeroIssue.val() !== '');
                if (!bValid) {alert('Tienes que indicar el número de issue'); return;}
            }else {
                bValid = bValid && (input_temaPrincipal.val() !== '');
                if (!bValid) { alert('Tienes que indicar un el tema principal'); return;}
            }
            if (bValid) Joomla.submitform(task, document.getElementById('form_gestionIssue'));
        }
        else if (task === task_cancelarNuevaIssue) { //back
            //window.history.go(-1);
            window.location.href = 'index.php?option=com_gestorcore';
        }else { //cancelar, modo desde revista
            Joomla.submitform(task, document.getElementById('form_gestionIssue'));
        }
    };
    
});