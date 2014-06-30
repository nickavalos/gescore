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
    
    //$("#hola").load('index.php?option=com_gestorcore&view=congresos&layout=default_nuevoCongreso&path=&tmpl=component #dialog-form_addCongreso');
    
    var div_panelBusquedaAvanzada = $("#toppanel");
    var div_panelFilter = $("div#panel-filter");
    
    var panel_filter_issue = $("#panel-filter-issue");
    var panel_filter_edicion = $("#panel-filter-edicion");
    
    var button_openSubfilter = $("#open_subfilter");
    var button_closeSubfilter = $("#close_subfilter");
    
    var contiene_inputs_activos = function(etiqueta) {
        var cont = 0;
        etiqueta.find('input').each(function() {
            if ($(this).val() !== "") cont++;
        });
        return cont !== 0;
    };
    
    var limpiarInputs = function(etiqueta) {
        etiqueta.find('input').each(function() {
            $(this).val('');
        });
    };
    
    var abrirPanelBusquedaAvanzada = function() {
        div_panelFilter.show();
        button_openSubfilter.hide();
        button_closeSubfilter.show();
    };
    
    var cerrarPanelBusquedaAvanzada = function() {
        div_panelFilter.hide();
        button_openSubfilter.show();
        button_closeSubfilter.hide();
    };
    
    var ocultarPanelBusquedaAvanzada = function() {
        div_panelBusquedaAvanzada.hide();
        $("#tableResultsSearch").css("top", "0px");
    };
    
    switch($("#filter-bar .filter-select select").val()) {
        case 'issue':
            if (contiene_inputs_activos(panel_filter_issue))  abrirPanelBusquedaAvanzada();
            //limpiarInputs(panel_filter_edicion);
            break;
        case 'edicion':
            if (contiene_inputs_activos(panel_filter_edicion)) abrirPanelBusquedaAvanzada();
            //limpiarInputs(panel_filter_issue);
            break;
        default:
            //limpiarInputs(panel_filter_issue);
            //limpiarInputs(panel_filter_edicion);
            ocultarPanelBusquedaAvanzada();
            break;
    }
    
    $("#filter-bar .button_clear").click(function() {
        document.id('filter_search').value = '';
        limpiarInputs(panel_filter_issue);
        limpiarInputs(panel_filter_edicion);
        this.form.submit();
    });
     
    $('#filter_incluirTopico').on('click', function () {
        if ($(this).is(':checked')) {
            //alert("aa");
            //$(this).prop('checked', false);
            document.id('filter_incluirTopsID').value='checked';
        }else {
            //$(this).prop('checked', false);
            document.id('filter_incluirTopsID').value='';
            //$('#filter_incluirTopico').removeAttr('checked');
        }
        this.form.submit();
    });
    
    /*
    $("div.panel_button").click(function(){
        $("div#panel_sub").animate({
                height: "500px"
        })
        .animate({
                height: "400px"
        }, "fast");
        $("div.panel_button").toggle();
    });	
	
    $("div#hide_button").click(function(){
        $("div#panel_sub").animate({
                height: "0px"
        }, "fast");
    });	*/
    
    
    
    
    
    // Expand Panel
    button_openSubfilter.click(function(){
        div_panelFilter.slideDown("slow");

    });	

    // Collapse Panel
    button_closeSubfilter.click(function(){
        div_panelFilter.slideUp("slow");	
    });		

    // Switch buttons from "Log In | Register" to "Close Panel" on click
    $("#toggle a").click(function () {
        $("#toggle a").toggle();
    });		
    
});