/****************************** Dialog: Nuevo Congreso *****************************/
window.addEvent('domready', function () {    
    
    var dialog_addCongreso = $( "#dialog-form_addCongreso" ),
        nomEntidad = $( "#nomEntidad" ),
        acronimoEntidad = $( "#acronimoEntidad" ),
        linkEntidad = $( "#linkEntidad" ),
        periodicidadEntidad = $( "#periodicidadEntidad" ),
        allFields_dialog_entidad = $( [] ).add( nomEntidad ).add( acronimoEntidad ).add( linkEntidad ).add( periodicidadEntidad ),
        tips = $( "#dialog-form_addCongreso .validateTips" );
    
    
    var editorialesEntidad = $("#selectEditorialesEntidad");    
    
    var button_addCategoria = $("#addNuevaCategoria");
    var div_htmlOculto = $("#htmlOculto");
    var div_listaCategorias = $("#div_listaCategorias");
    var ul_listaCategorias = $("#listaCategorias");
    
    
    $( ".sortable_entidad" ).sortable({
        placeholder: "ui-state-highlight"
    });
    $( ".sortable_entidad" ).disableSelection();
    
    button_addCategoria.click(function(){
        var nuevaCategoria = div_htmlOculto.children('.typeCategoria').clone();
        ul_listaCategorias.append(nuevaCategoria);
    });
    
    $(document.body).on('click', '.iconDeleteCriterio_preguntar', function(){
        var result = confirm("¿Quieres eliminar esta categoría?");
        if (result) {
            $(this).parent().parent('li').remove();
        }
    });
    
    $('#escogerTipoCategoria input[type=radio]').change(function() {
        if (this.value === 'lista') {
            div_listaCategorias.removeAttr("hidden");
        }
        else if (this.value === 'numerico') {
            div_listaCategorias.attr("hidden", true);
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
        return true;
    };
    var ejecutarNuevaEntidadCalidad = function(datos, textStatus, request){
        if (datos['existe']) { //Error
            alert("Ya existe una entidad de Calidad con ese nombre");
        }else {
            Joomla.submitform('entidadcalidad.nuevaEntidadCalidad', document.getElementById('form_nuevaEntidadCalidad'));
        }
    };
    var ejecutarError = function(xhr, textStatus, errorThrown){
        alert(errorThrown); 
    };
    
    Joomla.submitbutton = function(task) {
        if (task === 'entidadcalidad.nuevaEntidadCalidad') {
            var bValid = true;
            allFields_dialog_entidad.removeClass( "ui-state-error" );
            bValid = bValid && checkLength( nomEntidad, "Nombre de la entidad", 1, -1 );
            if ( bValid ) {
                var url ='index.php?option=com_gestorcore&controller=entidadcalidad' + 
                         '&task=existeEntidadCalidad&format=json';
                
                ajaxConnection(url, 'json', 'GET', ejecutarNuevaEntidadCalidad, ejecutarError, {nomEntidad: nomEntidad.val()});
                
                //Joomla.submitform(task, document.getElementById('form_nuevaEntidadCalidad'));
            }
        }
        else if (task === 'entidadcalidad.cancelarEntidadCalidad') {
            //Joomla.submitform(task, document.getElementById('form_nuevaEntidadCalidad'));
//            event.preventDefault();
//            history.back(1);
            window.location.href = 'index.php?option=com_gestorcore';
        }
    };
});