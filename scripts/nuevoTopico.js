/****************************** Dialog: Nuevo Congreso *****************************/
window.addEvent('domready', function () {    
    
    var dialog_addCongreso = $( "#dialog-form_addTopico" ),
        nomTopico = $( "#nomTopico" ),
        descripcionTopico = $( "#descripcionTopico" ),
        allFields_dialog_entidad = $( [] ).add( nomTopico ).add( descripcionTopico ),
        tips = $( "#dialog-form_addTopico .validateTips" );
    
    var select_topicosSeleccionados = $("#listSimilaresSeleccionados");
    var select_topicosSeleccionados_copy = $("#copy_listSimilaresSeleccionados");
    var checkBox_incluirDescripcion = $("#checkIncluirDescripcion");
    var input_addTopico = $( "#nuevoTopico_similares .criterioBusqueda" );
    var button_deleteTopico = $("#delete_topico");
    
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
 
    input_addTopico.catcomplete({
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
        minLength: 1,
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
    
    button_deleteTopico.click(function() { 
        //select_topicosSeleccionados.find(":selected").remove();
        select_topicosSeleccionados.find(":selected").each(function() {
            select_topicosSeleccionados_copy.find("[value='" + $(this).val() + "']").first().remove();
            $(this).remove();
        });
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
            object.addClass( "ui-state-error" );
            updateTips( "Required field: " + n + " no puede ser vacio." );
            return false;
        }
        return true;
    };
    var ejecutarNuevaEntidadCalidad = function(datos, textStatus, request){
        if (datos['existe']) { //Error
            alert("Ya existe un tópico con ese nombre");
        }else {
            Joomla.submitform('topico.nuevoTopico', document.getElementById('form_nuevoTopico'));
        }
    };
    var ejecutarError = function(xhr, textStatus, errorThrown){
        alert(errorThrown); 
    };
    
    Joomla.submitbutton = function(task) {
        if (task === 'topico.nuevoTopico') {
            var bValid = true;
            allFields_dialog_entidad.removeClass( "ui-state-error" );
            bValid = bValid && checkLength( nomTopico, "Nombre", 1, -1 );
            if ( bValid ) {
                var url ='index.php?option=com_gestorcore&controller=topico&task=existeTopico&format=json';
                ajaxConnection(url, 'json', 'GET', ejecutarNuevaEntidadCalidad, ejecutarError, {nomTopico: nomTopico.val()});
                //Joomla.submitform(task, document.getElementById('form_nuevaEntidadCalidad'));
            }
        }
        else if (task === 'topico.cancelarTopico') {
            //Joomla.submitform(task, document.getElementById('form_nuevaEntidadCalidad'));
//            event.preventDefault();
//            history.back(1);
            window.location.href = 'index.php?option=com_gestorcore';
        }
    };
});