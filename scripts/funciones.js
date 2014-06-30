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

var carpetaImagenes = "components/com_gestorcore/imgs/";

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

var enableButton = function(button) {
    button.removeAttr('disabled').addClass('pointer');
};

var disableButton = function(button) {
    button.attr('disabled', true).removeClass('pointer');
};


var crossSelect = function(target, tituloIzq, tituloDer) {
    target.crossSelect({
        rows: 10,
        listWidth: 220,
        font: '12',
        horizontal: "scroll",
        dblclick: true,
        clickSelects: false,
        clicksAccumulate: true,
        select_txt: "< Seleccionar",
        remove_txt: "Quitar >",
        selectAll_txt: "< Selec. todo",
        removeAll_txt: "Quitar todo >",
        tituloIzq: tituloIzq,
        tituloDer: tituloDer
    });
};

var printGraphic = function(elementGraphic, title, hashAnswers, type) {
    var array_keys = new Array();
    var array_values = new Array();

    var max_length = 0;

    for (var key in hashAnswers) {
        if (key.length > max_length) max_length = key.length;
        array_keys.push(key);
        array_values.push(hashAnswers[key]);
    }

    elementGraphic.highcharts({
        chart: {
            type: 'column',
            margin: [ 50, 50, 50 + max_length*3, 50]
        },
        title: {
            text: title
        },
        xAxis: {
            categories: array_keys,
            labels: {
                rotation: -45,
                align: 'right',
                style: {
                    fontSize: '11px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Número de valoraciones'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            formatter: function() {
                return '<b>'+ this.x +'</b><br/>'+
                    type + ' con esta valoracion: '+ Highcharts.numberFormat(this.y, 0);
            }
        },
        series: [{
            name: 'Population',
            data: array_values,
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',
                x: 4,
                y: 10,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        }]
    });
};


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

var rsc_jui_alert = {
    close: 'Cerrar',
    freeze_message: 'Freeze message'
};