/* 
 * CrossSelect. Modificated by Nick Avalos on 02/dic/2013 
 */
(function($) { 
	$.fn.crossSelect = function(options) {
		var pars = $.extend({}, jQuery.fn.crossSelect.defaults, options);
		var select, context, longestOpt, dimensions = new Array(), optionsList, chosenList, buttons;
		
		return this.each(function() {
			//if($(this).attr('multiple') === "multiple")
            if($(this).attr('multiple'))
			{	
				select = $(this).hide().wrap('<div class="jqxs"></div>');
				context = $(select).parent();
				getLongestOpt();
				setDimensions();
				populateLists();
				createCrossSelecter();
				addListeners();
			}
		});
		
		function createCrossSelecter() {
			buttons = '<div class="jqxs_buttons">';
			if(!pars.clickSelects)
			{
				buttons+= '<input type="button" value="'+pars.select_txt+'" class="jqxs_selectButton" disabled="disabled"></input><br><input type="button" value="'+pars.remove_txt+'" class="jqxs_removeButton" disabled="disabled"></input>';
			}
			buttons +='<br><input type="button" value="'+pars.selectAll_txt+'" class="jqxs_selectAllButton pointer"';
			if ($('option:not([selected=true])',select).size() === 0)
			{
				buttons+=' disabled="disabled"';
			}
			buttons+='></input><br><input type="button" value="'+pars.removeAll_txt+'" class="jqxs_removeAllButton"';
			var test = $('option',select);
			if ($('option[selected=true]',select).size() === 0)
			{
				buttons+=' disabled="disabled"';
			}		
			buttons+='></input></div>';
                        
            var cuerpo = "<table>";
            cuerpo += "<tr> <td> <center>" + pars.tituloIzq + "</center> </td>";
            cuerpo += "<td> </td>";
            cuerpo += "<td> <center>" + pars.tituloDer + "</center> </td> </tr>";
            cuerpo += "<tr><td>" + chosenList + "</td>";       
            cuerpo += "<td>" + buttons + "</td>";
            cuerpo += "<td>" + optionsList + "</td>"; 
            cuerpo += "</tr></table>";

            //$(context).append(chosenList + buttons + optionsList + '<div style="clear:left;"></div>');
            $(context).append(cuerpo + '<div style="clear:left;"></div>');
			//$('ul', context).css({height: dimensions['height'],width:dimensions['width']});
			$('ul', context).css({height: dimensions['height'], minWidth:400, maxWidth: 400});
			if (pars.horizontal === 'hide')
			{
				$('ul', context).css({'overflow-x': 'hidden'});
			}
		}
		
		function setDimensions() {	
			switch (pars.vertical)
			{
				case 'expand':
					dimensions['height'] = $(select).children('option').size() * (pars.font *1.25);
					break;
				case 'scroll':
					dimensions['height'] = (pars.font *1.25) * Math.min($(select).children('option').size(),pars.rows);
					break;
			}
			switch (pars.horizontal)
			{
				case 'expand':
					dimensions['width'] = Math.max(longestOpt + 10, pars.listWidth);
					break;
				case 'scroll':
					dimensions['width'] = pars.listWidth;
					if(longestOpt > pars.listWidth){dimensions['height'] = dimensions['height'] + 24;}
					break;
				case 'hide':
					dimensions['width'] = pars.listWidth; // remember to set overflow-x hidden
					break;
			}
		}
			
		function populateLists() {
            
			var optOrder = 0;
			optionsList ='<ul class="jqxs_optionsList">';
			chosenList='<ul class="jqxs_chosenList">';
			var newItem;
			$(select).children('option').each(function() {		
				newItem = '<li'; 
				if ($(this).attr('selected')) {
                                    newItem += ' class="jqxs_selected"';
                                }
				newItem += ' style="line-height: '+(pars.font *1.25)+'px; height:'+pars.font *1.25+'px;font-size: '+pars.font+'px;width:'+longestOpt+'px">';
				//newItem += ' style="line-height: '+(pars.font *1.25)+'px; height:'+pars.font *1.25+'px;font-size: '+pars.font+'px">';
				newItem+= $(this).text() + '<span>' + optOrder +'</span></li>';
				optionsList += newItem;
				if($(this).attr('selected'))
				{
					chosenList += newItem;
				}
				optOrder++;
			});
			chosenList +='</ul>';
			optionsList +='</ul>';
		}
		
		function getLongestOpt() {
				var text = "";
				$('option',select).each(function() {
					text+= $(this).text() +"<br />";
				});
                /*
                //original 
                $(context).append('<span class="jqxs_ruler" style="font-size:'+pars.font+'px">'+text+'</span>');
				longestOpt = $('span', context).width();
                $('span', context).remove();*/
                
                //añadido
                $('#content-box .m').append('<div id="size_width"></div>');
                $("#size_width").append('<span class="jqxs_ruler" style="font-size:'+pars.font+'px">'+text+'</span>');
                longestOpt = $('span', '#size_width').width();
				$('span', '#size_width').remove();
		}
		
		function addListeners() {
			var thisSelect = select;
			var thisOptions = $('.jqxs_optionsList', context);
			var thisChosen = $('.jqxs_chosenList', context);
			var thisSelBut = $('.jqxs_selectButton', context);
			var thisRemBut = $('.jqxs_removeButton', context);
			var thisSelAllBut = $('.jqxs_selectAllButton', context);
			var thisRemAllBut = $('.jqxs_removeAllButton', context);
			if(pars.clickSelects)
			{
				$('li', thisOptions).click(selectNow);
				$('li', thisChosen).click(removeNow);
			} else {
				$('li', context).click(itemFocus);
				$(thisSelBut).click(selectMany);
				$(thisRemBut).click(removeMany);
			}
			if(pars.dblclick)
			{
				$('li', thisOptions).dblclick(selectNow);
				$('li', thisChosen).dblclick(removeNow);
			}
			$(thisSelAllBut).click(selectAll);
			$(thisRemAllBut).click(removeAll);
			
			function itemFocus() {
				if($(this).hasClass('jqxs_focused'))
				{
					$(this).removeClass('jqxs_focused');				
					if($(this).parent().children('.jqxs_focused').length === 0)
					{
						disable(thisSelBut);
						disable(thisRemBut);
					}
					return;
				}
				if(!pars.clicksAccumulate)
				{
					$('li',thisOptions).removeClass('jqxs_focused');
					$('li',thisChosen).removeClass('jqxs_focused');
				} else {
					if($(this).parent().hasClass('jqxs_optionsList'))
					{
						$('li',thisChosen).removeClass('jqxs_focused');
					} else {
						$('li',thisOptions).removeClass('jqxs_focused');
					}
				}
				$(this).addClass('jqxs_focused');
				if ($(this).parent().hasClass('jqxs_optionsList')){
                                        
					enable(thisSelBut);
					disable(thisRemBut);
				}else{
					enable(thisRemBut);
					disable(thisSelBut);
				}
			}
			
			function selectOne() {
                                //alert("one");
				var position = $('span',this).text();
				var option = $(thisSelect).children('option')[position];
				if(!$(option).attr('selected')) //alert1
				{
                                        //alert("entra")
					$(option).attr('selected','selected'); //alert3
					$(this).removeClass('jqxs_focused');
					if(pars.clickSelects)
					{
						$(this).clone().appendTo(thisChosen).click(removeNow)
					} else {
						if(pars.dblclick) 
						{
							$(this).clone().appendTo(thisChosen).click(itemFocus).dblclick(removeNow);
						} else {
							$(this).clone().appendTo(thisChosen).click(itemFocus);
						}
					}
					$(this).addClass('jqxs_selected');
				}
			}
			
			function removeOne() {
				var position = $('span',this).text();
				var option = $(thisSelect).children('option')[position];
                                //alert($(option).attr('selected'));
				if($(option).attr('selected'))
				{
					$(option).removeAttr('selected'); //alerta2
					$($(thisOptions).children('li')[position]).removeClass('jqxs_selected');
					$(this).remove();
				}
			}
			
			function selectNow() {
				$(this).each(selectOne);
				adjustButtons('sel');
			}
			
			function removeNow() {
				$(this).each(removeOne);
				adjustButtons('rem');
			}
			
			function selectMany() {
                                //alert("many");
				$('.jqxs_focused',thisOptions).each(selectOne);
//                                $(thisOptions).find(".jqxs_focused").each(function(){
//                                    alert("aaa");   
//                                });
				adjustButtons('sel');
			}
			
			function removeMany() {
				$('.jqxs_focused',thisChosen).each(removeOne);
				adjustButtons('rem');
			}
			
			function selectAll(){
                                //alert("selectAll")
                                $('li',thisOptions).each(selectOne);
				disable(thisRemBut);
				adjustButtons('sel');
			}
			
			function removeAll(){
                                //alert("remAll");
				$('li',thisChosen).each(removeOne);
				disable(thisSelBut);
				adjustButtons('rem');
			}
			function adjustButtons(action) {
				switch(action)
				{
					case 'sel':
						disable(thisSelBut);
						enable(thisRemAllBut);
						if($('li:not(.jqxs_selected)',thisOptions).size() === 0 ) {disable(thisSelAllBut);}
						break;
					case 'rem':
						disable(thisRemBut);
						enable(thisSelAllBut);
						if($('li',thisChosen).size() === 0 ) {disable(thisRemAllBut);}
						break;
				}
			}
		}
		
		function enable(button){
                        //alert("enabble");
			$(button).addClass('jqxs_active').attr('disabled', false).addClass('pointer');
		}
		
		function disable(button){
                        //alert("disable");
			$(button).removeClass('jqxs_active').attr('disabled', true).removeClass('pointer');
		}	
	};
})(jQuery);
jQuery.fn.crossSelect.defaults = {
	vertical: 'scroll',
	horizontal: 'hide',
	listWidth: 150,
	font: 12,
	rows: 8,
	dblclick: true,
	clickSelects: false,
	clicksAccumulate: false,
	select_txt: 'select',
	remove_txt: 'remove',
	selectAll_txt: 'select all',
	removeAll_txt: 'remove all',
        tituloIzq: 'titulo1',
        tituloDer: 'titulo2'
};