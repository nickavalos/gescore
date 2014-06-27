/*
created: Nick Avalos on Date: 05/11/2013
 */

window.addEvent('domready', function () {
    
    var submenu = $("#submenu-box .m");
    
    var uriBase = 'index.php?option=com_gestorcore&';
    var categoryCongreso = 'Congreso';
    var categoryRevista = 'Revista';
    var categoryEdicion = 'Edición';
    var categoryEditorial = 'Editorial';
    var categoryEntidadIndiceCalidad = 'Entidad de Calidad';
    var categoryIssue = 'Issue';
    var categoryTopico = 'Topico';
    
    
    submenu.children().remove();
    submenu.append($("#module-submenu"));
    submenu.append("<div class='clr'></div>");
    
    var busquedaRapida = '<div class="fltrt ui-widget"><input id="buscadorRapido" class="ui-corner-all" size="50" placeholder=" Búsqueda Rápida"/></div>'
    submenu.children("#module-submenu").append(busquedaRapida);
    
    //$("#submenu-box .m").last().after($('<input type="text" name="files[]" class="file" /><br />'));
    
    /*************************************BUSCADOR RÁPIDO******************************************************/
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
    
    function log( topico ) {
        //$( "<div>" ).text( message ).prependTo( "#log" );
        //$( "#log" ).scrollTop( 0 );
        var newOption = "<option value='" + topico + "'>" + topico + '</option>';
        select_topicosSeleccionados.append(newOption);
        select_topicosSeleccionados_copy.append("<option value='" + topico + "' selected>" + topico + '</option>');
    }
 
    $( "#buscadorRapido" ).catcomplete({
        source: function( request, response ) {
            $.ajax({
                url: "index.php?option=com_gestorcore&controller=gescore&task=getResults_rapidSearch&format=ajax",
                dataType: "json",
                data: {
                    filter: request.term,
                    //incluirDescripcion: checkBox_incluirDescripcion.is(':checked')
                },
                success: function( data ) {
                    response( $.map( data, function( item ) {
                        return {
                            label: item.title,
                            category: item.categoria,
                            id: item.id
                        };
                    }));
                }
            });
        },
        minLength: 1,
        select: function( event, ui ) {
            //var etiq = ui.item.category === 'Nombre'?ui.item.label:ui.item.nomTopico; 
            //log( ui.item ? "Selected: " + etiq : "Nothing selected, input was " + this.value);
            //log(etiq);
            //var uriBase = 'l'
            var uri = '';
            switch(ui.item.category) {
                case categoryCongreso:
                    uri = 'controller=congreso&task=infoCongreso&id=' + ui.item.id;
                    break;
                case categoryRevista:
                    uri = 'controller=revista&task=infoRevista&id=' + ui.item.id;
                    break;
                case categoryEditorial:
                    uri = 'controller=edicion&task=infoEditorial&id=' + ui.item.id;
                    break;
                case categoryEntidadIndiceCalidad:
                    uri = 'view=infoEntidadCalidad&id=' + ui.item.id;
                    break;
                case categoryTopico:
                    uri = 'view=infoTopico&id=' + ui.item.id;
                    break;
                case categoryEdicion:
                    var ids = ui.item.id.split("%%");
                    uri = 'controller=congreso&task=infoEdicion&orden=' + ids[0] + "&congreso=" + ids[1];
                    break;
                default:
                    uri = '';
                    break;
            }
            window.location.href = uriBase + uri;
        },
        open: function() {
            $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
            $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
            $(this).val('');
        }
    });
   
    
    
});