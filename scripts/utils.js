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

function ajaxConnection(mUrl, mDataType, mType, mSuccess, mError, mData){
    $.ajax({
        url: mUrl,
        dataType: mDataType,
        type: mType,
        success: mSuccess,
        error: mError,
        data: mData
    });
}



window.addEvent('domready', function () {
    
    var carpeta_imagenes = 'components/com_gestorcore/imgs/';
    
    $(".date_ini").datepicker({
        changeMonth: true,
        changeYear: true,

        showOn: "button",
        buttonImage: carpeta_imagenes + 'iconCalendar.png',
        buttonImageOnly: true,

        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        showAnim: 'slideDown',
        onClose: function( selectedDate ) {
            $( ".date_end" ).datepicker( "option", "minDate", selectedDate );
            $(this).keyup();
        }
    });
    $(".date_end").datepicker({
        changeMonth: true,
        changeYear: true,

        showOn: "button",
        buttonImage: carpeta_imagenes + 'iconCalendar.png',
        buttonImageOnly: true,

        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        showAnim: 'slideDown',
        onClose: function( selectedDate ) {
             $(this).keyup();
        }
    });
    $(".date_jQuery").datepicker({
        changeMonth: true,
        changeYear: true,
        showOn: "button",
        buttonImage: carpeta_imagenes + 'iconCalendar.png',
        buttonImageOnly: true,
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        showAnim: 'slideDown',
    });
    
    /*******************************************************/
    //$("select[multiple=\"multiple\"]").crossSelect();
    /*$('.crossSelect').crossSelect({
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
        tituloIzq: "Tópicos para la edición",
        tituloDer: "Tópicos del congreso"
    });
    
    $('.crossSelectRevista').crossSelect({
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
    });*/
    
    $(document.body).on('click', '.iconDelete_sinPreguntar', function(){
        $(this).parent().parent('tr').remove();
    });
    
    $(document.body).on('click', '.iconDelete_preguntar', function(){
        if ($("#checkBox_noPreg").is(':checked')) {
            $(this).parent().parent('tr').remove();
        }
        else {
            var result = confirm("¿Quieres eliminar este elemento?");
            if (result === true) {
                $(this).parent().parent('tr').remove();
            }
        }
    });
    
    $(document.body).on('change', '.selectorEntidadCalidad', function(){
        var entidad = $(this).find(':selected').val();
        var url = 'index.php?option=com_gestorcore&controller=congreso&task=getCategoriasEntidadCalidad&format=ajax';
        var selectorCategoria = $(this).parent('td').parent('tr').find('.selectorCategoriaCalidad');
        ajaxConnection(
            url,
            'json',
            'GET',
            function(datos, textStatus, request) {
                selectorCategoria.children().remove();
                var contenido = '';
                if (datos['tipo'] === 'lista') {
                    contenido = $('<select name="selectorCategoriaCalidad[]"></select>');
                    for (i = 0; i < datos['lista'].length; i++) {
                        var newOption = "<option value='" + datos['lista'][i]['categoria'] + "'>" + datos['lista'][i]['categoria'] + '</option>';
                        contenido.append(newOption);
                    }
                }
                else if(datos['tipo'] === 'numerico') {
                    contenido = $('<input size="30" placeholder=" Valoración numérica (e.j: 7.45)" name="selectorCategoriaCalidad[]"/>');
                    contenido.addClass("selectorSpinnerEdicion");
                }
                selectorCategoria.append(contenido);
            },
            function(xhr, textStatus, errorThrown){
                alert(errorThrown); 
            },
            {entidad: entidad}
        );
    });
    
    /************************** Restringir orden a numeros ******************************************/
    //$(".selectorSpinnerEdicion").keydown(function(event) {
    $(document.body).on('keydown', '.selectorSpinnerEdicion', function(event) {
         // Allow: backspace, delete, tab, escape, enter and .
        if ( $.inArray(event.keyCode,[46,8,9,27,13,190]) !== -1 ||
             // Allow: Ctrl+A
            (event.keyCode === 65 && event.ctrlKey === true) || 
             // Allow: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        else {
            // Ensure that it is a number and stop the keypress
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault(); 
            }   
        }
    });
    
    
    
});