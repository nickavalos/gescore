/*
 * Datepicker for Jeditable
 *
 * Copyright (c) 2011 Piotr 'Qertoip' Włodarek
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Depends on jQuery UI Datepicker
 *
 * Project home:
 *   http://github.com/qertoip/jeditable-datepicker
 *
 */

// add :focus selector
jQuery.expr[':'].focus = function( elem ) {
  return elem === document.activeElement && ( elem.type || elem.href );
};

$.editable.addInputType( 'datepicker', {

    /* create input element */
    element: function( settings, original ) {
      var form = $( this ),
          input = $( '<input />' );
      input.attr( 'autocomplete','off' );
      form.append( input );
      return input;
    },
    
    /* attach jquery.ui.datepicker to the input element */
    plugin: function( settings, original ) {
      var form = this,
          input = form.find( "input" );

      // Don't cancel inline editing onblur to allow clicking datepicker
      settings.onblur = 'nothing';

      datepicker = {
        onSelect: function() {
          // clicking specific day in the calendar should
          // submit the form and close the input field
          form.submit();
        },
        
        onClose: function() {
          setTimeout( function() {
            if ( !input.is( ':focus' ) ) {
              // input has NO focus after 150ms which means
              // calendar was closed due to click outside of it
              // so let's close the input field without saving
              original.reset( form );
            } else {
              // input still HAS focus after 150ms which means
              // calendar was closed due to Enter in the input field
              // so lets submit the form and close the input field
              form.submit();
            }
            
            // the delay is necessary; calendar must be already
            // closed for the above :focus checking to work properly;
            // without a delay the form is submitted in all scenarios, which is wrong
          }, 150 );
        }
      };
    
      if (settings.datepicker) {
        jQuery.extend(datepicker, settings.datepicker);
      }

      input.datepicker(datepicker);
    }
} );

/*
$.editable.addInputType('autocomplete', {
    element : $.editable.types.text.element,
    plugin : function(settings, original) {
        $('input', this).autocomplete(settings.autocomplete.url, {                                                 
            dataType:'json',
            parse : function(data) {                                                                                                                    
                return $.map(data, function(item){
                    return {
                        data : item,
                        value : item.Key,
                        result: item.value                                                                                     
                    }
                })
            },
            formatItem: function(row, i, n) {                                                        
                return row.value;
            },
            mustMatch: false,
            focus: function(event, ui) {                                                
                $('#example tbody td[title]').val(ui.item.label);
                return false;
            }
        });                                        
    }
});

$("#example tbody td[title]").editable(function(value,settings){
    return value;
    }, 
    {                                     
    type      : "autocomplete",
    tooltip   : "Click to edit...",            
    autocomplete : 
        { 
            url : "autocompleteeg.aspx" 
        }});     
oTableexample = $('#example').dataTable({
    "bInfo": false
 }); 
*/
