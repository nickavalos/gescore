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
    
    /*********************PRIVATE VARS*******************************/
    var carpeta_imagenes = 'components/com_gestorcore/imgs/';
    var icon_lockEdicion = $("#iconlock");
    var div_topicoFromCongreso = $(".divTopicosFromEntidad");
    var select_edicion = $("#selectorEdicion");
    var select_congreso = $("#selectorCongreso");
    var select_topicosSeleccionados = $("#listTopicosSeleccionados");
    var select_topicosSeleccionados_copy = $("#copy_listTopicosSeleccionados");
    var input_congresoActual = $('#congresoActual');
    var input_edicionActual = $('#ordenEdicion');
    var checkBox_nuevosTopicos = $("#checkNuevosTopicos");
    
    /**************************INIT******************************/
    
    crossSelect($(".divTopicosFromEntidad select"), "Tópicos para la edición", "Tópicos del congreso");
    
    /***********************LISTENERS************************/
    icon_lockEdicion.click(function(event) {
        if (select_edicion.attr("readonly")) {
            $(this).attr("src", carpeta_imagenes + 'iconUnlock.png');
            select_edicion.removeAttr("readonly").removeClass("sololectura");
        }
        else {
            $(this).attr("src", carpeta_imagenes + 'iconLock.png');
            select_edicion.attr("readonly", true).addClass("sololectura");
        }
    });
    
    checkBox_nuevosTopicos.removeAttr("checked");
    
    select_congreso.change(function() {
        var parametros = {
            nomCongreso: $(this).val()
    	};
        //var array = new Object();
        //array['variable1'] = "login";
        var url ='index.php?option=com_gestorcore&controller=congreso' +
                 '&task=getTopicosCongreso_ajax&format=ajax&nomCongreso='+select_congreso.val()+
                 '&congresoActual='+input_congresoActual.val()+'&ordenEdicion='+input_edicionActual.val();
        ajaxConnection(
            url,
            'json',
            'GET',
            function(datos, textStatus, request){
                div_topicoFromCongreso.children().remove();
                select_topicosSeleccionados.children().remove();
                select_topicosSeleccionados_copy.children().remove();
                var newCrossSelect = $("#htmlOculto .crossSelect").clone();
                for (i = 0; i < datos.length; i++) {
                    if (datos[i]['select'] !== "noPertCongreso") {
                        var string = "<option value='" + datos[i]['nomTopico'] + "'>" + datos[i]['nomTopico'] + '</option>';
                        if (datos[i]['select'] === "si") {
                            string = "<option value='" + datos[i]['nomTopico'] + "' selected>" + datos[i]['nomTopico'] + '</option>';
                        }
                        newCrossSelect.append(string);
                    }
                    else {
                        var nuevaOtraOpt = "<option value='" + datos[i]['nomTopico'] + "'>" + datos[i]['nomTopico'] + '</option>';
                        select_topicosSeleccionados.append(nuevaOtraOpt);
                        select_topicosSeleccionados_copy.append("<option value='" + datos[i]['nomTopico'] + "' selected>" + datos[i]['nomTopico'] + '</option>');
                    }
                    if (datos[i]['select'] === "ultimaEdicionC") {
                        $("#ultimaEdicionNumber").text(datos[i]['max']);
//                        if ($('#selectorCongreso_editar').val() === input_congresoActual.val()) {
//                            select_edicion.attr('value', $('#ordenEdicion').val());
//                        }
//                        else {
//                            var ordenSiguiente = parseInt(datos[i]['max']) + 1;
//                            select_edicion.attr('value', ordenSiguiente);
//                        }   
                    }
                }
                div_topicoFromCongreso.append(newCrossSelect);
                
                //$('#topicosFromCongreso_edit').remove();
                crossSelect(newCrossSelect, "Tópicos para la edición", "Tópicos del congreso");
            },
            function(xhr, textStatus, errorThrown){
                alert(errorThrown); 
            }
            //parametros
        );
    });
    
});