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



window.addEvent('domready', function () {
    
    
    //var checkBox_otrosTopicos = $("#checkOtrosTopicos");
    var checkBox_nuevosTopicos = $("#checkNuevosTopicos");
    //var checkBox_incluirDescripcion = $("#checkIncluirDescripcion");
    var checkBox_specialIssue  = $("#checkSpecialIssue");
    //var button_addTopico = $("#buttonAddTopico");
    //var button_deleteTopico = $("#delete_topico");
    //var button_similaresTopico = $("#similares_topico");
    //var button_addValoracion = $("#buttonAddValoracion");
    //var button_addRevista = $("#add_revista");
    //var select_topicosSeleccionados = $("#listTopicosSeleccionados");
    //var select_topicosSeleccionados_copy = $("#copy_listTopicosSeleccionados");
    var select_revista = $("#selectorRevista");
    var select_volumenesRevista = $("#selectorVolumenesRevista");
    var select_numeroIssue = $("#selectorNumeroIssue");
    
    //var input_copyIssn = $("#issnRevistaActual");
    var input_nuevoVolumen = $("#nuevoVolumen");
    var span_ultimoNumeroVolumenIssue = $("#ultimoNumeroVolumen");
    
    var div_topicosFromRevista = $("#divTopicosFromRevista");
    //var div_selectorVolumen = $("#divSelectorVolumen");
    
    var idSpecialIssue = 'specialIssue';
    var idNuevoVolumen = '-1';
    
    
    /**************************INIT******************************/
    
    crossSelect($("#divTopicosFromRevista select"), "Tópicos para la Issue", "Tópicos de la Revista");
    
    
    /*******************Listeners*********************/
    
    
    /*checkBox_otrosTopicos.removeAttr("checked");
    checkBox_otrosTopicos.click(function() {
        $("#otrosTopicos .ui-widget").slideToggle();
    });*/
    
    checkBox_nuevosTopicos.removeAttr("checked");
    
    checkBox_specialIssue.removeAttr("checked");
    
    
    /*^*************************************************************************/
    
    var reiniciarSelectVolumen = function() {
        select_volumenesRevista.children().remove();
        select_volumenesRevista.append("<option value='-1'>Nuevo Volumen</option>");
        input_nuevoVolumen.removeClass('sololectura').removeAttr('disabled');
    };
    
    select_revista.change(function() {
        //alert(select_revista.val());
        var datos = {
            issnRevista : $(this).val()
        };
        var url ='index.php?option=com_gestorcore&controller=revista&task=getTopicosRevista&format=ajax';
        ajaxConnection(
            url,
            'json',
            'GET',
            function(datos, textStatus, request){
                div_topicosFromRevista.children().remove();
                reiniciarSelectVolumen();
                //$("#tableTopicosEdicion tbody").children().remove();
                var newCrossSelect = $("#htmlOculto .crossSelectRevista").clone();
                for (i = 0; i < datos.length; i++) {
                    if (datos[i]['typeData'] === 'volumen' && datos[i]['volumen'] !== idSpecialIssue) {
                        var string = "<option value='" + datos[i]['volumen'] + "'>" + datos[i]['volumen'] + '</option>';
                        select_volumenesRevista.append(string);
                    }
                    else {
                        var string = "<option value='" + datos[i]['nomTopico'] + "'>" + datos[i]['nomTopico'] + '</option>';
                        newCrossSelect.append(string);
                    }
                }
                div_topicosFromRevista.append(newCrossSelect);
                crossSelect(newCrossSelect, "Tópicos para la Issue", "Tópicos de la Revista");
                select_volumenesRevista.change();
            },
            function(xhr, textStatus, errorThrown){
                alert(errorThrown); 
            },
            datos
        ); 
    });
    
    select_volumenesRevista.change(function() {
        //alert(select_revista.val());
        if ($(this).val() === idNuevoVolumen) {
            span_ultimoNumeroVolumenIssue.text('-');
            select_numeroIssue.val('1');
        } else {
            var datos = {
                issnRevista : select_revista.val(),
                volumen : $(this).val()
            };
            var url ='index.php?option=com_gestorcore&controller=revista&task=getUltimoNumeroVolumen&format=ajax';
            ajaxConnection(
                url,
                'json',
                'GET',
                function(datos, textStatus, request) {
                    span_ultimoNumeroVolumenIssue.text(datos["numero"]);
                    select_numeroIssue.val(parseInt(datos["numero"]) + 1);
                },
                function(xhr, textStatus, errorThrown){
                    alert(errorThrown); 
                },
                datos
            ); 
        }
    });
    
    
    

    /***************************************alternativa********************************************************/
    function split( val ) {
      return val.split( /,\s*/ );
    }
    /*function extractLast( term ) {
      return split( term ).pop();
    }
    
    var listaEditoriales = [];
    
    $( "#issnRevista2" )
        // don't navigate away from the field on tab when selecting an item
        .bind( "keydown", function( event ) {
            if ( event.keyCode === $.ui.keyCode.TAB &&
                $( this ).data( "ui-autocomplete" ).menu.active ) {
                event.preventDefault();
            }
        })
        .autocomplete({
            source: function( request, response ) {
                $.getJSON( "index.php?option=com_gestorcore&controller=revista&task=getNomEditoriales_ajax&format=ajax", {
                    term: extractLast( request.term )
                }, response );
            },
            search: function() {
                // custom minLength
                var term = extractLast( this.value );
                if ( term.length < 2 ) {
                    return false;
                }
            },
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            select: function( event, ui ) {
                var terms = split( this.value );
                // remove the current input
                terms.pop();
                // add the selected item
                terms.push( ui.item.value );
                // add placeholder to get the comma-and-space at the end
                terms.push( "" );
                this.value = terms.join( ", " );
                return false;
            }
        });
    */
    
    
    
});