/*
created: Nick Avalos on Date: 05/11/2013
 */

window.addEvent('domready', function () {
    
    var selector_numeroIssue = $("#selectorNumeroIssue");
    var checkBox_nuevosTopicos = $("#checkNuevosTopicos");
    //var checkBox_incluirDescripcion = $("#checkIncluirDescripcion");
    var checkBox_specialIssue  = $("#checkSpecialIssue");
    //var button_addTopico = $("#buttonAddTopico");
    //var button_deleteTopico = $("#delete_topico");
    //var button_addValoracion = $("#buttonAddValoracion");
    //var button_addRevista = $("#add_revista");
    var select_topicosSeleccionados = $("#listTopicosSeleccionados");
    var select_topicosSeleccionados_copy = $("#copy_listTopicosSeleccionados");
    var select_revista = $("#selectorRevista");
    var select_volumenesRevista = $("#selectorVolumenesRevista");
    
    var input_copyIssn = $("#issnRevistaActual");
    var input_copyVolumen = $("#volumenIssueActual");
    var input_copyNumero = $("#numeroIssueActual");
    var input_nuevoVolumen = $("#nuevoVolumen");
    var span_ultimoNumeroVolumenIssue = $("#ultimoNumeroVolumen");
    
    var div_topicosFromRevista = $("#divTopicosFromRevista");
    var div_selectorVolumen = $("#divSelectorVolumen");
    
    var idSpecialIssue = 'specialIssue';
    var idNuevoVolumen = '-1';
    
    /**************************INIT******************************/
    
    crossSelect($("#divTopicosFromRevista select"), "Tópicos para la Issue", "Tópicos de la Revista");
    
    /*******************Listeners*********************/
   
    
    checkBox_nuevosTopicos.removeAttr("checked"); //no move - changed
    
    
    //checkBox_specialIssue.removeAttr("checked");
    if (checkBox_specialIssue.is(':checked')) { //changed
        //checkBox_specialIssue.click();
        div_selectorVolumen.slideToggle();
        $("#iconlock").attr('hidden', true);
    }
    
    /*^**********************************REVISTA Y VOLUMEN***************************************/
    
    var reiniciarSelectVolumen = function() {
        select_volumenesRevista.children().remove();
        select_volumenesRevista.append("<option value='-1'>Nuevo Volumen</option>");
        input_nuevoVolumen.removeClass('sololectura').removeAttr('disabled');
    };
    
    select_revista.change(function() { //changed
        //alert(select_revista.val());
        var datos = {
            issnRevista : $(this).val(),
            revistaActual: input_copyIssn.val(),
            volumenActual: input_copyVolumen.val(),
            numeroActual: input_copyNumero.val()
        };
        var url ='index.php?option=com_gestorcore&controller=revista&task=getTopicosRevista_complete&format=ajax';
        ajaxConnection(
            url,
            'json',
            'GET',
            function(datos, textStatus, request){
                div_topicosFromRevista.children().remove();
                select_topicosSeleccionados.children().remove();
                select_topicosSeleccionados_copy.children().remove();
                reiniciarSelectVolumen();
                //$("#tableTopicosEdicion tbody").children().remove();
                var newCrossSelect = $("#htmlOculto .crossSelectRevista").clone();
                for (var i = 0; i < datos.length; i++) {
                    if (datos[i]['typeData'] === 'volumen') {
                        if (datos[i]['volumen'] !== idSpecialIssue) {
                            var optVolumen = "<option value='" + datos[i]['volumen'] + "'>" + datos[i]['volumen'] + '</option>';
                            select_volumenesRevista.append(optVolumen);
                        }
                    }else {
                        if (datos[i]['typeData'] === 'noPertRevista') {
                            var nuevaOtraOpt = "<option value='" + datos[i]['nomTopico'] + "'>" + datos[i]['nomTopico'] + '</option>';
                            select_topicosSeleccionados.append(nuevaOtraOpt);
                            select_topicosSeleccionados_copy.append("<option value='" + datos[i]['nomTopico'] + "' selected>" + datos[i]['nomTopico'] + '</option>');
                        }
                        else { //si o no
                            var string = "<option value='" + datos[i]['nomTopico'] + "'>" + datos[i]['nomTopico'] + '</option>'; //no
                            if (datos[i]['typeData'] === 'si') {
                                string = "<option value='" + datos[i]['nomTopico'] + "' selected>" + datos[i]['nomTopico'] + '</option>';
                            }
                            newCrossSelect.append(string);
                        }
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
    
    select_volumenesRevista.change(function() { //changed
        //alert(select_revista.val());
        if ($(this).val() === idNuevoVolumen) {
            span_ultimoNumeroVolumenIssue.text('-');
            selector_numeroIssue.val('1');
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
                    //selector_numeroIssue.val(parseInt(datos["numero"]) + 1);
                },
                function(xhr, textStatus, errorThrown){
                    alert(errorThrown); 
                },
                datos
            ); 
        }
    });
    
    
});