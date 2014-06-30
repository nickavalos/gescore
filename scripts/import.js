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

//window.addEvent('domready', function () {

$(document).ready(function(){
    var scienceDirect = false;
    /*

    Copyright (c) 2011 Remko Caprio (@sciversedev and r.caprio@elsevier.com)
    See https://github.com/sciversedev
    With thanks to Sam Adams (sea36@cam.ac.uk)


    MIT License

    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to deal
    in the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
    THE SOFTWARE.

    */

    /**
    * In your gadget specification xml include:
    *    <UserPref name="contentApiKey" datatype="hidden" default_value="your-api-key-here" />
    *    <UserPref name="secureAuthtoken" datatype="hidden" />
    *    
    * Example:
    *	myclient = new ContentApiClient();
    *	myclient.setSearchCluster("SCIDIR");
    *	myclient.setView("COMPLETE");
    *	myclient.setResultCount(5);
    *	myclient.setSearchCompleteCallback(contentApiCallback);
    *	myclient.setDivId(divid);
    *	myclient.execute(searchterms);
    */

    /**
     * Creates a JavaScript object ContentApiClient
     */  
    function ContentApiClient() {
        // use OpenSocial UserPref properties specified in the gadget specification file
        // User must include UserPref name="contentApiKey" datatype="hidden" default_value="your-api-key-goes-here" 
        /*var prefs = new gadgets.Prefs();
        this.apikey = prefs.getString("contentApiKey");*/
        this.authtoken = "sat_98BD1D9FAE2E74AB63AEF30B2D45D2056299DE30E83BDD5BDB1AE99E0A234EBEC7F5B0CB39D835CC1D101503F8B24E654438DEAA836B1A17D34A8E676A7E7125A51A0D047DC6DFD5F77CCEBD19A245FEB51FBBEE1C7D3A02491D2E8E80F3474789821360474854331B972FDE0C292981C69BCD45F178DCBFCA819BE6D308905E3FBDD0BF6B332B154D0C1BE2B6E3A6FC860C57ADBF5ADB9C3A677FAAD1155AEAD7E0A22E720E7995CCEC07EB1208B46356DEF4966A7E23D8";
        this.apikey = 'f54281c9e361405f4efb0fdec15234c7';//sushitos
        //this.apikey = '082447154c1f6889bdf62936aa8be164'; //localhost
        //this.apikey = '157df35638551533a12aac351a6e567a'; //bytehost
        
    }
    
    /**
     * Callback function for the Content API Request
     */
    function contentApiCallback(){
        //alert("funciona");
        /*
        var output = "";

        if(myclient.data === null){
            var display = "<b><font color='red'>return object == null</font></b>"
            document.getElementById(myclient.divid).innerHTML = output;
            return;
        }

        var textJson = gadgets.json.parse(myclient.data);
        if(!textJson){
            var display = "<b><font color='red'>parsing results returned nothing</font></b>"
            document.getElementById(myclient.divid).innerHTML = output;
            return;
        }

        var entries = textJson['search-results']['entry'];

        $.each(entries, function(i1, entry){
            var title = entry['dc:title'];
            output = output + (i1+1) + ". " + title + "<br>";
        });

        document.getElementById(myclient.divid).innerHTML = "Search results:<br>"+output;*/
    }

    /**
     * execute method for the ContentApiClient. 
     * The prototype keyword is used to create a singleton essentially    
     */
    ContentApiClient.prototype.execute = function(searchterms) {

        var url = "http://api.elsevier.com/content/search/index:"+this.searchcluster;  

        url = url + "?count="+this.resultcount;
        //url = url + "&view="+this.view;
        url = url + "&apiKey="+this.apikey;
        url = url + "&access_token=" + this.authtoken;
        url = url + "&reqId=" + encodeURIComponent('nick.avalos@outlook.com');
        url = url + "&httpAccept=application\/json";
        var query = encodeURIComponent(searchterms);
        url = url + "&query=" + query;      
        //url = url + "&callback=?";      

        var requestHeaders = {};
        requestHeaders['X-ELS-APIKey'] = this.apikey;
        requestHeaders['X-ELS-Authtoken'] = this.authtoken;
        requestHeaders['Accept'] = "application/json";
        requestHeaders['Access-Control-Allow-Origin'] = "http://api.elsevier.com//";
        
        
        /*var params = {};
        params[gadgets.io.RequestParameters.HEADERS] = requestHeaders;

        var sciverse = this;
        gadgets.sciverse.makeRequest(url, function(response) {
            sciverse.response = response;
            sciverse.error = response.errors;
            if (response.data) {
                sciverse.data = response.data;
            }
            if (sciverse.callback) {
                sciverse.callback();
            }
        }, params);*/
        
        
        var sciverse = this;
        var datos = {};
        datos['username'] = 'nick.avalos@outlook.com';
        datos['password'] = '';
/*
        $.ajax({
            //url: url + '&jsoncallback=?',
            url: 'http://api.elsevier.com/authenticate?apiKey=' + this.apikey,
            dataType: 'json',
            type: 'GET',
            success: function(datos, textStatus, request){
                //if (datos['service-error']) alert("ERROR");
                //alert(datos['search-results']);
                alert("funcio");
            },
            error: function(xhr, textStatus, errorThrown){
                console.log(xhr.responseText);
                console.log(textStatus);
                alert(errorThrown);
            }
        });*/
        /*
        var xmlhttp;
        xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                alert(xmlhttp.responseText);
                alert("success");
            }
        }

        xmlhttp.open("GET", url, true);
        xmlhttp.setRequestHeader("Content-Type", "application/json");
        xmlhttp.setRequestHeader("Access-Control-Allow-Origin", "*");
        xmlhttp.setRequestHeader("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
        xmlhttp.setRequestHeader("Access-Control-Allow-Methods","POST, GET, OPTIONS, DELETE, PUT, HEAD");
        xmlhttp.setRequestHeader("Access-Control-Max-Age","1728000");   
        xmlhttp.send(JSON.stringify(calculate));*/
        
        if (true) {
        $.ajax({
            //url: url + '&jsoncallback=?',
            url: url,
            dataType: 'json',
            //contentType: "application/json",
            type: 'GET',
            /*xhrFields: {
                withCredentials: true
            },*/
            /*    
            headers: {
                'Access-Control-Allow-Origin': 'http://api.elsevier.com//'
            },
            */
            /*
            jsonpCallback: function() {
                alert("hola");
            },*/
            /*async: false,*/
            //callback: contentApiCallback(),
            //jsonpCallback: 'contentApiCallback',
            //jsonp: false,
            //async: false,
            //jsonpCallback: sciverse.contentApiCallback(),
            crossDomain: true,
            beforeSend: function(respons) {
                
                //xhr.setRequestHeader('Authorization', '');
                //xhr.setRequestHeader('Access-Control-Allow-Origin', 'http://api.elsevier.com//');
                respons.setRequestHeader('X-ELS-APIKey', this.apikey);
                respons.setRequestHeader('X-ELS-Authtoken', this.authtoken);
                //xhr.setRequestHeader('Authority', $("#token_user").val());
                //xhr.setRequestHeader('Accept', 'jsonp');
                respons.setRequestHeader('Access-Control-Allow-Origin', 'http://api.elsevier.com//');
                respons.setRequestHeader('Access-Control-Allow-Origin', '*');
                //xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                
                //requestHeaders['Access-Control-Allow-Origin'] = "http://api.elsevier.com//";
                
                
            },
            success: function(datos, textStatus, request){
                //if (datos['service-error']) alert("ERROR");
                //alert(datos['search-results']);
                console.log(JSON.stringify(datos));
                alert("funcio");
            },
            error: function(xhr, textStatus, errorThrown){
                console.log(xhr.responseText);
                console.log(textStatus);
                alert(errorThrown);
                //alert("error");
            }
        });
        }
    }
    /**
     * Setter methods
     */
    ContentApiClient.prototype.setSearchCompleteCallback = function(callback) {
        this.callback = callback;
    };
    ContentApiClient.prototype.setSearchCluster = function(searchcluster) {
        this.searchcluster = searchcluster;
    };
    ContentApiClient.prototype.setResultCount = function(resultcount) {
        this.resultcount = resultcount;
    };
    ContentApiClient.prototype.setView = function(view) {
        this.view = view;
    };
    ContentApiClient.prototype.setDivId = function(divid) {
        this.divid = divid;
    };
    
    /*************************************VARS************************************************************************/
    var tableResults = $("#tableResultsSearch_import tbody");
    var filaResults = $("#htmlOculto .filaResultados");
    
    
    var input_search = $("#filter_search");
    var button_search = $("#import_search")
    
    var busquedaFecha = $("#escogerTipoBusquedaFecha");
    var select_month = $('#busquedaFecha .select_month');
    var input_year = $('#busquedaFecha .input_year');
    var input_year_ini = $('#busquedaFecha .year_ini');
    var input_year_end = $('#busquedaFecha .year_end');
    
    
    var select_sourceType = $("#filter-bar .import_sourceType");
    var select_documentType = $("#filter-bar .import_documentType");
    var select_subarea = $("#filter-bar .import_subarea");
    
    var select_sort_by = $("#filter_inferior .sort_by");
    var select_sort_direction = $("#filter_inferior .sort_direction");
    var select_numResults_porPagina = $("#filter_inferior .results_per_page");
    var button_importar_seleccionados = $("#filter_inferior .importar_seleccionados");
    var div_txt_numResults = $("#filter_inferior .txt_numResults");
    var span_numeroResultados = $("#filter_inferior .numResults");
    
    var checkboxes_buscar_en = $("#import_checkboxes .content");
    
    var div_accordion_imports = $("#htmlOculto .accordion_imports")
    var import_log_correctos = $("#import_log_correctos");
    var import_log_incorrectos = $("#import_log_incorrectos");
    var div_pagination = $("#import_pagination");
    
    var primera_busqueda = false;
    var no_select = '*';
    
    /****************************LOGS**********************************/
    var accordion_imports_correctos = div_accordion_imports.clone();
    var accordion_imports_incorrectos = div_accordion_imports.clone();

    accordion_imports_correctos.find('.expander').simpleexpand();
    accordion_imports_incorrectos.find('.expander').simpleexpand();
        
    import_log_correctos.jui_alert({
        containerClass: "container1 ui-widget",
        message: 'Se ha importado correctamente <strong><span class="num_imports">0</span></strong> publicaciones',
        timeout: 0,
        messageAreaClass: "jui_alert_message_area ui-corner-all",
        messageIconClass: ""
    });
    import_log_incorrectos.jui_alert({
        containerClass: "container1 ui-widget",
        message: 'No se ha podido importar <strong><span class="num_imports">0</span></strong> publicaciones',
        timeout: 0,
        messageAreaClass: "jui_alert_message_area ui-state-error ui-corner-all",
        messageIconClass: "jui_alert_icon ui-icon ui-icon-alert"
    });
    
    import_log_correctos.find('.jui_alert_message_area').append(accordion_imports_correctos);
    import_log_incorrectos.find('.jui_alert_message_area').append(accordion_imports_incorrectos);
    
    
    /**************************BUSQUEDA POR FECHA*******************************************/        
    $('#import_checkboxes .seleccionar_todo').change(function() {
        checkboxes_buscar_en.find('input[type=checkbox]').prop('checked', $(this).is(":checked"));
    });
    
    busquedaFecha.find('input[type=radio]').change(function() {
        $('#busquedaFecha .toggle div').toggle();
    });
    
    input_search.keypress(function(event) {
        if (event.keyCode === 13) button_search.click();
    });
    
    /********************************* LISTENERS ******************************************************/
    select_sort_by.change(function() {
        button_search.click();
    });
    select_sort_direction.change(function() {
        button_search.click();
    });
    select_numResults_porPagina.change(function() {
        button_search.click();
    });
    
    var reiniciar_log = function(target_log) {
        target_log.find(".num_imports").text("0");
        target_log.find('.content ul').children().remove();
    };
    
    var updateNumImports = function(log_actualizar, numero) {
        var target = import_log_correctos;
        if (log_actualizar === 'incorrecto') target= import_log_incorrectos;
        
        var numeroActual = parseInt(target.find(".num_imports").text());
        target.find(".num_imports").text(numeroActual + numero);
    };
    
    
    
    //button_importar_seleccionados.click(function() {      //con boton en submenu
    Joomla.submitbutton = function(task) {                  //con boton en el titulo
        var numImports = tableResults.find('input[type=checkbox]:checked').size();
        if (numImports === 0) {
            alert("Seleccione al menos una publicación para importar");
            return;
        }
        var ok = confirm("Se va a importar " + numImports  + " publicaciones");
        if (ok) {
            tableResults.find('input[type=checkbox]:checked').each(function() {
                var fila = $(this).parent().parent();
                var datosEnviados = {};
                fila.children().each(function() {
                    if ($(this).attr('class') !== 'center') datosEnviados[$(this).attr('class')] = $(this).text();
                });

                /* Ocultamos y reiniciamos los logs*/
                import_log_correctos.hide();
                import_log_incorrectos.hide();
                reiniciar_log(import_log_correctos);
                reiniciar_log(import_log_incorrectos);

                ajaxConnection(
                    'index.php?option=com_gestorcore&controller=revista&task=postFromImport&format=raw',
                    'json',
                    'POST',
                    function(datos, textStatus, request){
                        if (datos['error']) {
                            var fila = $('<li>' + datos['tituloPublicacion'] + ' (Error: <strong>' + datos['error']  + '</strong>)</li>');
                            accordion_imports_incorrectos.find('.content ul').append(fila);
                            updateNumImports('incorrecto', 1);
                            if (import_log_incorrectos.find('.num_imports').text() === '1') import_log_incorrectos.show(); //si hay al menos uno mostrar
                        }else {
                            var link = '<a href="index.php?option=com_gestorcore&controller=revista&task=infoIssue&volumen=' + datos['volumen'] + 
                                       '&numero=' + datos['numero'] + '&revista=' + datos['issnRevista'] + '">click aquí</a>';
                            var fila = $('<li>' + datos['tituloPublicacion'] + ' (Link: ' + link  + ')</li>');
                            accordion_imports_correctos.find('.content ul').append(fila);
                            updateNumImports('correcto', 1);
                            if (import_log_correctos.find('.num_imports').text() === '1') import_log_correctos.show(); //si hay al menos uno mostrar                        
                        }

                    },
                    function(xhr, textStatus, errorThrown){
                        alert(errorThrown); 
                    },
                    datosEnviados
                ); 
            });
            //import_log_correctos.show();
            //import_log_incorrectos.show();
        }
    };


    if (scienceDirect) {
        var myclient;

        function getcontext() {
            gadgets.sciverse.getContextInfo(init);
        }   

        var divid = "output";
        document.getElementById(divid).innerHTML = "";

        //var searchterms = context.searchTerms;

        myclient = new ContentApiClient();
        myclient.setSearchCluster("SCIDIR");
        myclient.setView("COMPLETE");
        myclient.setResultCount(5);
        myclient.setSearchCompleteCallback(contentApiCallback);
        myclient.setDivId(divid);
        myclient.execute("heart attack");
        //gadgets.util.registerOnLoadHandler(getcontext);
    }
    else { //OCOPUS
        callback = function(){ 
            //document.sciverseForm.searchButton.disabled = false;
            button_search.attr('disabled', false);
            div_txt_numResults.show();  
            
            var numResults = sciverse.getTotalHits();
            var numResultsPorPagina = sciverse.getNumResults();
            
            span_numeroResultados.text(numResults);
            
            div_pagination.find('.pagination').jqPagination({
                //link_string	: '/?page={page_number}',
                max_page	: Math.ceil(numResults/numResultsPorPagina),
                paged		: function(page) {
                    tableResults.children().remove();
                    runSearch(select_numResults_porPagina.val(), (page-1)*numResultsPorPagina);
                }
            });
            
            var results = sciverse.getSearchResults();
            results.results.forEach(function(entry, index) {
                var nuevaEntrada = filaResults.clone().addClass("row" + index%2);
                nuevaEntrada.find('input[type=checkbox]').attr('id', 'cb' + index); //"id" al checkbox
                nuevaEntrada.children('.tituloEntidadPublicadora').append(entry['sourcetitle']);
                nuevaEntrada.children('.sourceType').append(entry['doctype']);
                nuevaEntrada.children('.tituloPublicacion').append(entry['title']);
                nuevaEntrada.children('.issnRevista').append(entry['issn']);
                nuevaEntrada.children('.volumen').append(entry['vol']);
                nuevaEntrada.children('.numero').append(entry['issue']);
                nuevaEntrada.children('.fechaPublicacion').append(entry['pubdate']);
                nuevaEntrada.children('.autor').append(entry['firstauth']);
                nuevaEntrada.children('.linkScopus').children('a').attr("href", entry['inwardurl']);
                tableResults.append(nuevaEntrada);
            });
            
        };
        
        var buildQuery = function(numCheckBoxesSelected) {
            var query = '(';
            var palabraBuscada = input_search.val();
            
            checkboxes_buscar_en.find('input[type=checkbox]:checked').each(function(index) {
                query += $(this).val() + '(' + palabraBuscada + ')';
                if (index < (numCheckBoxesSelected - 1)) query += ' OR ';
            });
            query += ')';
            
            if (select_sourceType.val() !== no_select)  query += ' AND SRCTYPE(' + select_sourceType.val() + ')';
            if (select_documentType.val() !== no_select)  query += ' AND DOCTYPE(' + select_documentType.val() + ')';
            if (select_subarea.val() !== no_select)  query += ' AND SUBJAREA(' + select_subarea.val() + ')';
            
            switch(busquedaFecha.find('input[type=radio]:checked').val()) {
                case 'monthyear':
                    if (select_month.val() !== no_select && input_year.val()) query += ' AND PUBDATETXT(' + select_month.val() + ' ' + input_year.val() + ')';   
                    break;
                case 'intervalo':
                    if (input_year_ini.val()) query += ' AND PUBYEAR > ' + (parseInt(input_year_ini.val()) - 1);
                    if (input_year_end.val()) query += ' AND PUBYEAR < ' + (parseInt(input_year_end.val()) + 1);
                    break;
            }
            return query;
        };
        
        
        var runSearch = function(resultsPorPagina, offset) {
            //document.sciverseForm.searchButton.disabled = true;
            
            if (!input_search.val()) {
                alert('Campo de filtro no puede ser vacio');
                return;
            }
            
            var numCheckBoxesSelected = checkboxes_buscar_en.find('input[type=checkbox]:checked').size();
            if (numCheckBoxesSelected > 0 ) {
                button_search.attr('disabled', true);       //desabilitamos el boton de busqueda
                $("#tableResultsSearch_import thead").find('input[type=checkbox]').attr('checked', false);  //quitamos el checkall
                
                /* Ocultamos y reiniciamos los logs de import*/
                import_log_correctos.hide();
                import_log_incorrectos.hide();
                
                //reiniciar_log(import_log_correctos);
                //reiniciar_log(import_log_incorrectos);
                
                /*Objeto de búsqueda*/
                var varSearchObj = new searchObj();
                //varSearchObj.setSearch(document.sciverseForm.searchString.value);
                
                varSearchObj.setSort(select_sort_by.val());
                varSearchObj.setSortDirection(select_sort_direction.val());
                varSearchObj.setSearch(buildQuery(numCheckBoxesSelected));
                //varSearchObj.setSearch('ABS(' + input_search.val() + ') AND NOT TITLE(' + input_search.val() + ')');
                varSearchObj.setNumResults(resultsPorPagina);
                varSearchObj.setOffset(offset);
                
                sciverse.runSearch(varSearchObj); 
                primera_busqueda = true;                    //se ha producido al menos una busqueda
                //sciverse.search(varSearchObj);            //with output
            }
            else {
                alert('Error: selecciona al menos un elemento a buscar');
            }
        };
        
        button_search.click(function() {
            div_pagination.children().remove();
            div_pagination.append($("#htmlOculto .pagination").clone());
            
            div_txt_numResults.hide();                  //ocultamps los resultados
            tableResults.children().remove();
            if (primera_busqueda) {
                //$('.pagination').jqPagination('option', 'current_page', 1);
                //$('.pagination').jqPagination('option', 'max_page', Math.ceil(parseInt(span_numeroResultados.text())/select_numResults_porPagina.val()));
            }
            runSearch(select_numResults_porPagina.val(), 0);
            
        });
        
        //debug.setDebug(true);
        sciverse.setApiKey("f54281c9e361405f4efb0fdec15234c7");
        sciverse.setCallback(callback);
        //runSearch();
    }
});

