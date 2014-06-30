/**
 * This file is part of the project Gestor de Congresos y Revistas en Joomla (GesCORE).
 *
 * @package		GesCORE backend 
 * @subpackage  Scripts 
 * @copyright   Copyright (C) 2014 Open Source Matters, Inc. All rights reserved.
 * @authors		Nick Avalos, Enric Mayol, Mª José Casany
 * @link		https://github.com/nickavalos/gescore
 * @license		License GNU General Public License
 *
 * GesCORE is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * GesCORE is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GesCORE. If not, see <http://www.gnu.org/licenses/>.
 */


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