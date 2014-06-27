/*
created: Nick Avalos on Date: 05/11/2013
 */

window.addEvent('domready', function () {
    var categorias = $("#categoriasEntidadCalidad");
    var htmlOculto = $("#htmlOculto");
    
    //var graphic = listAnswersQuestion.children("#graphicAnswer");
    var hashCountAnswers_ediciones = {};
    var hashCountAnswers_issues = {};
    
    var valoracionesEdiciones = [];
    var valoracionesIssues = [];
    
    htmlOculto.children('.valoracionesEdiciones').find('option').each(function() {
        valoracionesEdiciones.push($(this).val());
    });
    htmlOculto.children('.valoracionesIssues').find('option').each(function() {
        valoracionesIssues.push($(this).val());
    });
    
    if ($("#tipoCategoria").text() === 'lista') {
        categorias.find('li').each(function() { //inicializamos valores
            var categoria = $(this).children('label').text();
            hashCountAnswers_ediciones[categoria] = 0;
            hashCountAnswers_issues[categoria] = 0;
        });

        for(var key in hashCountAnswers_ediciones) {
            for(var i = 0; i < valoracionesEdiciones.length; i++) {
                if (key === valoracionesEdiciones[i]) hashCountAnswers_ediciones[key]++;
            }
            for(var i = 0; i < valoracionesIssues.length; i++) {
                if (key === valoracionesIssues[i]) hashCountAnswers_issues[key]++;
            }
        }
    }
    else {
        var maxValorEdiciones = Math.max.apply(null, valoracionesEdiciones);
        if (maxValorEdiciones === 0) maxValorEdiciones = 1; 
        var cotaSuperior = Math.ceil(maxValorEdiciones/10)*10;
        var incremento = Math.floor(cotaSuperior/10);
        
        for (var i = 0; i < cotaSuperior; i += incremento) { //inicializamos valores
            var index = "[" + i.toString() + ".." + (i + incremento).toString() + "]";
            hashCountAnswers_ediciones[index] = 0;
            hashCountAnswers_issues[index] = 0;
        }
        for(var i = 0; i < valoracionesEdiciones.length; i++) {
            var numeroPretratado = Math.floor(valoracionesEdiciones[i]) - (Math.floor(valoracionesEdiciones[i])%incremento);
            var numero = numeroPretratado === cotaSuperior? (numeroPretratado-incremento):numeroPretratado; 
            var key = "[" + numero.toString() + ".." + (numero + incremento).toString() + "]"; 
            hashCountAnswers_ediciones[key]++;
        }
        for(var i = 0; i < valoracionesIssues.length; i++) {
            var numeroPretratado = Math.floor(valoracionesEdiciones[i]) - (Math.floor(valoracionesEdiciones[i])%incremento);
            var numero = numeroPretratado === cotaSuperior? (numeroPretratado-incremento):numeroPretratado; 
            var key = "[" + numero.toString() + ".." + (numero + incremento).toString() + "]"; 
            hashCountAnswers_issues[key]++;
        }
    }
    
    printGraphic($("#graphicEdiciones"), "Distribución de sus Valoraciones", hashCountAnswers_ediciones, 'Ediciones');
    printGraphic($("#graphicIssues"), "Distribución de sus Valoraciones", hashCountAnswers_issues, 'Issues');
    
    $('.jeditable_info_entidadCalidad').editable("index.php?option=com_gestorcore&controller=entidadCalidad&task=put_editEntidadCalidad&format=raw", {
        indicator : "<img src='" + carpetaImagenes + "loading.gif'>",
        method    : "PUT",
        submit    : "OK",
        cancel    : "Cancel",
        tooltip   : "Doble click para editar...",
        event     : "dblclick",
        //style     : "inherit",
        width     : "auto",
        submitdata : function(value, settings) {
            return {entidadCalidad_copy: $("#nomEntidadCalidad_copy").val()};
        }
    });
    $('.jeditable_textArea_entidadCalidad').editable("index.php?option=com_gestorcore&controller=entidadCalidad&task=put_editEntidadCalidad&format=raw", {
        indicator : "<img src='" + carpetaImagenes + "loading.gif'>",
        type      : "textarea",
        rows      : "3",
        cols      : "37",
        method    : "PUT",
        submit    : "OK",
        cancel    : "Cancel",
        tooltip   : "Doble click para editar...",
        event     : "dblclick",
        style     : "inherit",
        submitdata : function(value, settings) {
            return {entidadCalidad_copy: $("#nomEntidadCalidad_copy").val()};
        }
    });
    
    
});