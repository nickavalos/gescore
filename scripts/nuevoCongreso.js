/****************************** Dialog: Nuevo Congreso *****************************/
window.addEvent('domready', function () {    
    
    var dialog_addCongreso = $( "#dialog-form_addCongreso" ),
        nomEntidad = $( "#nomEntidad" ),
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
    );
    
    
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
        return true;
    };
    var ejecutarNuevoCongreso = function(datos, textStatus, request){
        if (typeof(datos['error']) !== 'undefined') { //Error
            alert(datos['error']);
        }else { //todo correcto: hacemos redirect desde el controlador
            Joomla.submitform('nuevoCongreso', document.getElementById('form_nuevoCongreso'));
        }
    };
    var ejecutarError = function(xhr, textStatus, errorThrown){
        alert(errorThrown); 
    };
    
    Joomla.submitbutton = function(task) {
        //alert(task);
        if (task === 'nuevoCongreso') {
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
                //Joomla.submitform(task, document.getElementById('form_nuevoCongreso'));
            }
        }
        else if (task === 'cancelarNuevoCongreso') {
            //Joomla.submitform(task, document.getElementById('form_nuevoCongreso'));
            //window.history.go(-1);
            window.location.href = 'index.php?option=com_gestorcore';
        }
    };
});