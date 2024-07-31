/**
 * Combine scripts for Customizer Controls.
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

( function( api ) {

/*--------------- Upsell ------------------------*/

    api.sectionConstructor['mt-upsell'] = api.Section.extend( {

        // No events for this type of section.
        attachEvents: function () {},

        // Always make the section active.
        isContextuallyActive: function () {
            return true;
        }
    } );

} )( wp.customize );

jQuery(document).ready(function($) {

    "use strict";

/*--------------- Repeater field -----------------------------*/

    // function for repeater field
    function easy_store_refresh_repeater_values(){
        $(".es-repeater-field-control-wrap").each(function(){
            
            var values = []; 
            var $this = $(this);
            
            $this.find(".es-repeater-field-control").each(function(){
            var valueToPush = {};   

            $(this).find('[data-name]').each(function(){
                var dataName = $(this).attr('data-name');
                var dataValue = $(this).val();
                valueToPush[dataName] = dataValue;
            });

            values.push(valueToPush);
            });

            $this.next('.es-repeater-collector').val(JSON.stringify(values)).trigger('change');
        });
    }

    $('#customize-theme-controls').on('click','.es-repeater-field-title',function(){
        $(this).next().slideToggle();
        $(this).closest('.es-repeater-field-control').toggleClass('expanded');
    });

    $('#customize-theme-controls').on('click', '.es-repeater-field-close', function(){
        $(this).closest('.es-repeater-fields').slideUp();;
        $(this).closest('.es-repeater-field-control').toggleClass('expanded');
    });

    $("body").on("click",'.es-repeater-add-control-field', function(){

        var fLimit = $(this).parent().find('.field-limit').val();
        var fCount = $(this).parent().find('.field-count').val();
        if( fCount < fLimit ) {
            fCount++;
            $(this).parent().find('.field-count').val(fCount);
        } else {
            $(this).before('<span class="es-limit-msg"><em>Only '+fLimit+' repeater field will be permitted.</em></span>');
            return;
        }

        var $this = $(this).parent();
        if(typeof $this != 'undefined') {

            var field = $this.find(".es-repeater-field-control:first").clone();
            if(typeof field != 'undefined'){
                
                field.find("input[type='text'][data-name]").each(function(){
                    var defaultValue = $(this).attr('data-default');
                    $(this).val(defaultValue);
                });
                
                field.find(".es-repeater-icon-list").each(function(){
                    var defaultValue = $(this).next('input[data-name]').attr('data-default');
                    $(this).next('input[data-name]').val(defaultValue);
                    $(this).prev('.es-repeater-selected-icon').children('i').attr('class','').addClass(defaultValue);
                    
                    $(this).find('li').each(function(){
                        var icon_class = $(this).find('i').attr('class');
                        if(defaultValue == icon_class ){
                            $(this).addClass('icon-active');
                        }else{
                            $(this).removeClass('icon-active');
                        }
                    });
                });

                field.find(".attachment-media-view").each(function(){
                    var defaultValue = $(this).find('input[data-name]').attr('data-default');
                    $(this).find('input[data-name]').val(defaultValue);
                    if(defaultValue){
                        $(this).find(".thumbnail-image").html('<img src="'+defaultValue+'"/>').prev('.placeholder').addClass('hidden');
                    }else{
                        $(this).find(".thumbnail-image").html('').prev('.placeholder').removeClass('hidden');   
                    }
                });

                field.find('.es-repeater-fields').show();

                $this.find('.es-repeater-field-control-wrap').append(field);

                field.addClass('expanded').find('.es-repeater-fields').show(); 
                $('.accordion-section-content').animate({ scrollTop: $this.height() }, 1000);
                easy_store_refresh_repeater_values();
            }

        }
        return false;
     });
    
    $("#customize-theme-controls").on("click", ".es-repeater-field-remove",function(){
        if( typeof  $(this).parent() != 'undefined'){
            $(this).closest('.es-repeater-field-control').slideUp('normal', function(){
                $(this).remove();
                easy_store_refresh_repeater_values();
            });
        }
        return false;
    });

    $("#customize-theme-controls").on('keyup change', '[data-name]',function(){
        easy_store_refresh_repeater_values();
        return false;
    });

    /*Drag and drop to change order*/
    $(".es-repeater-field-control-wrap").sortable({
        orientation: "vertical",
        update: function( event, ui ) {
            easy_store_refresh_repeater_values();
        }
    });

    $('body').on('click', '.es-repeater-icon-list li', function(){
        var icon_class = $(this).find('i').attr('class');
        $(this).addClass('icon-active').siblings().removeClass('icon-active');
        $(this).parent('.es-repeater-icon-list').prev('.es-repeater-selected-icon').children('i').attr('class','').addClass(icon_class);
        $(this).parent('.es-repeater-icon-list').next('input').val(icon_class).trigger('change');
        easy_store_refresh_repeater_values();
    });

    $('body').on('click', '.es-repeater-selected-icon', function(){
        $(this).next().slideToggle();
    });

});

/*--------------- Radio Images Control ------------------------*/
    wp.customize.controlConstructor['es-radio-image'] = wp.customize.Control.extend({
        ready: function() {
            'use strict';
            var control = this;

            // Change the value
            this.container.on( 'click', 'input', function() {
                control.setting.set( jQuery( this ).val() );
            });
        }
    });

/*--------------- Toggle Control ------------------------------*/
    wp.customize.controlConstructor['es-toggle'] = wp.customize.Control.extend({
        ready: function(){
            'use strict';
            var control = this,
                checkboxValue = control.setting._value;
            // Save the value
            this.container.on( 'change', 'input', function() {
                checkboxValue = ( jQuery( this ).is( ':checked' ) ) ? true : false;
                control.setting.set( checkboxValue );
            });
        }
    });