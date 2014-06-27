/****************************** Dialog: Nuevo Congreso *****************************/
window.addEvent('domready', function () {    
    
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
    
    var task_nuevaRevista = 'nuevaRevista';
    var task_cancelarNuevaRevista= 'cancelarNuevaRevista';
    
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
        if (typeof(datos['error']) !== 'undefined') { //Error
            alert(datos['error']);
        }
        else {
            Joomla.submitform(task_nuevaRevista, document.getElementById('form_nuevaRevista'));
        }
    };
    var ejecutarError = function(xhr, textStatus, errorThrown){
        alert(errorThrown); 
    };
    
    Joomla.submitbutton = function(task) {
        //alert(task);
        if (task === task_nuevaRevista) {
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
                //Joomla.submitform(task, document.getElementById('form_nuevaRevista'));
            }
        }
        else if (task === task_cancelarNuevaRevista) {
            //Joomla.submitform(task, document.getElementById('form_nuevaRevista'));
            //window.history.go(-1);
            window.location.href = 'index.php?option=com_gestorcore';
        }
    };
    
});