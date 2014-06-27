/*
created: Nick Avalos on Date: 05/11/2013
 */

/*var datePicker = function(){ 
    
    
};*/


window.addEvent('domready', function () {
    
    var carpeta_imagenes = 'components/com_gestorcore/imgs/';
    var lock_edicion = $("#iconlock");
    var select_edicion = $("#selectorEdicion");
    var select_congreso = $("#selectorCongreso");
    //var checkBox_nuevosTopicos = $("#checkNuevosTopicos");
    //var checkBox_otrosTopicos = $("#checkOtrosTopicos");
    var span_ultimaEdicion = $("#ultimaEdicionNumber");
    var div_topicosFromCongreso = $(".divTopicosFromEntidad");
    
    var noCongresSelected = '-1';
    var requestFromMenu = '-1';
    
    
    /**************************INIT******************************/
    
    crossSelect($(".divTopicosFromEntidad select"), "Tópicos para la edición", "Tópicos del congreso");
    
    /*********************** LISTENERS *********************/
    lock_edicion.click(function(event) {
        if (select_edicion.attr("readonly")) {
            $(this).attr("src", carpeta_imagenes + 'iconUnlock.png');
            select_edicion.removeAttr("readonly").removeClass("sololectura");
        }
        else {
            $(this).attr("src", carpeta_imagenes + 'iconLock.png');
            select_edicion.attr("readonly", true).addClass("sololectura");
        }
    });
    
    //checkBox_nuevosTopicos.removeAttr("checked");
    
    /*checkBox_otrosTopicos.removeAttr("checked");
    checkBox_otrosTopicos.click(function() {
        $("#otrosTopicos .ui-widget").slideToggle();
    });*/
    
    select_congreso.change(function() {
        if ($(this).val() === noCongresSelected) { //reiniciar
//            div_topicosFromCongreso.children().remove();
//            var newCrossSelect = $("#htmlOculto .crossSelect").clone();
//            div_topicosFromCongreso.append(newCrossSelect);
//            crossSelect(newCrossSelect);
//            span_ultimaEdicion.text('0');
//            select_edicion.attr('value', 1);
//            return;
        }
        var datos = {
            nomCongreso : $(this).val()
        };
        var url ='index.php?option=com_gestorcore&controller=congreso&task=getTopicosCongreso&format=ajax';
        ajaxConnection(
            url,
            'json',
            'GET',
            function(datos, textStatus, request){
                div_topicosFromCongreso.children().remove();
                //$("#tableTopicosEdicion tbody").children().remove();
                var newCrossSelect = $("#htmlOculto .crossSelect").clone();
                for (var i = 0; i < datos.length; i++) {
                    if (datos[i]['select'] === 'ultimaEdicionC') {
                        if (datos[i]['max'] === null) datos[i]['max'] = 0;
                        span_ultimaEdicion.text(datos[i]['max']);
                        select_edicion.attr('value', parseInt(datos[i]['max']) + 1);
                    }
                    else {
                        var string = "<option value='" + datos[i]['nomTopico'] + "'>" + datos[i]['nomTopico'] + '</option>';
                        newCrossSelect.append(string);
                    }
                }
                div_topicosFromCongreso.append(newCrossSelect);
                crossSelect(newCrossSelect, "Tópicos para la edición", "Tópicos del congreso");
            },
            function(xhr, textStatus, errorThrown){
                alert(errorThrown); 
            },
            datos
        ); 
    });
    
    
    
    
});