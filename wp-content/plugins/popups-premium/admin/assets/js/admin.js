SPUP_ADMIN = (function ( $ ) {
    /*TODO we should unqueue free version js and just rely on premium one.*/
    var spu_editor = '';
	$(document).ready(function(){

        init_color_picker();
        $("#spu-appearance :input").not(".spu-color-field").change(applyStyles);


        $('.spu_reset_stats').on('click', function(){
            return confirm( spuvar.l18n.reset_stats );
        })

		//Toogle trigger boxes on init
		checkTriggerMethodP( $("#spu_trigger").val() );
		
		//Toogle trigger boxes on change
		$("#spu_trigger").change(function(){
			checkTriggerMethodP( $(this).val() );
		})
        /**
         * Updates on position change
         */
        $('#spu_position').on('change', function(){
            var $editor     = SPUP_ADMIN.spu_editor,
                val         = $(this).val(),
                trigger_row = $('#spu_trigger').closest('tr'),
                aninm_row   = $('#spu_animation').closest('tr');
            //update editor
            $editor.alterClass('spu-position-*', 'spu-position-'+ val )
            if( val == 'top-bar' || val == 'bottom-bar') {
                $editor.find('.spu-box-container *:not("p, .spu-fields-container, .spu-fields-container *")').remove();
            }
            if( val == 'after-content' ) {
                trigger_row.fadeOut();
                aninm_row.fadeOut();
            } else {
                if( ! trigger_row.is(':visible') ) {
                    trigger_row.fadeIn();
                    aninm_row.fadeIn();
                }
            }
        });
        /**
         * Ajax to retrieve optin lists
         */
        $('#spu_optin').change(function(){
            var spinner = $('.optin-spinner'),
                $editor =SPUP_ADMIN.spu_editor,
                _this   = $(this);

            if( _this.val() == '' ){
                $('.optin_opts').removeClass('visible');
                remove_optin_form();
                $editor.alterClass('spu-theme', '' );
            } else if ( _this.val() == 'custom')  {
                $('.optin_opts').removeClass('visible');
                $('.optin_theme').addClass('visible');
                remove_optin_form();
            } else {
                add_optin_form();
                init_color_picker();
                $('.optin_opts').addClass('visible').fadeIn();
                spinner.fadeIn();
                _this.prop("disabled", true);
                $.ajax({
                    method: "POST",
                    url: ajaxurl,
                    data: {action: "spu_get_optin_lists", optin: _this.val()}
                }).done(function (data) {
                    spinner.fadeOut();
                    _this.prop('disabled', false);
                    if (data) {
                        $('#spu_optin_list').html(data);
                        $('.optin_list').fadeIn();
                    } else {
                        $('#spu_optin_list').html('');
                    }
                });
            }
        });

        /**
         * Function to retrieve list groups
         */
        $('#spu_optin_list').change(function(){
            var spinner = $('.optin-spinner'),
                _this   = $(this);
            spinner.fadeIn();
            _this.prop( "disabled", true );
            $.ajax({
                method: "POST",
                url: ajaxurl,
                data: { action: "spu_get_optin_list_segments", list: _this.val(), optin : $('#spu_optin').val() }
            }).done(function( data ) {
                spinner.fadeOut();
                _this.prop('disabled',false);
                if( data ) {
                    $('.optin_list_segments .result').html(data);
                    $('.optin_list_segments').fadeIn();
                } else {
                    $('.optin_list_segments .result').html('');
                    $('.optin_list_segments').fadeOut();
                }
            });

        });

        /**
         * Update optin name field
         */
        $("#spu_optin_display_name").change(function(){
            var $editor = SPUP_ADMIN.spu_editor,
                name_field = '<input type="text" class="spu-fields spu-name" placeholder="'+spup_js.opts.optin_name_placeholder+'" name="spu-name"/>';
            $editor.toggleClass('with-spu-name');
            if( $(this).val() == '1') {
                $editor.find('#spu-optin-form').prepend(name_field);
            } else {
                $editor.find('.spu-fields.spu-name').remove();
            }
        });

        /**
         * Update placeholders & texts
         */
        $("#spu_optin_placeholder").blur(function () {
            var $editor = SPUP_ADMIN.spu_editor,
                placeholder = $(this).val();

            $editor.find('.spu-email').prop('placeholder',placeholder);
        });
        $("#spu_optin_name_placeholder").blur(function () {
            var $editor = SPUP_ADMIN.spu_editor,
                placeholder = $(this).val();

            $editor.find('.spu-name').prop('placeholder',placeholder);
        });
        $("#spu_optin_submit").blur(function () {
            var $editor = SPUP_ADMIN.spu_editor,
                text = $(this).val();

            $editor.find('.spu-submit').text(text);
        });

        /**
         * Update optin theme
         */
        $('.optin_themes .theme').on('click', function(){
            var $editor =SPUP_ADMIN.spu_editor,
                new_theme = $(this).data('theme');
            $('.optin_themes .theme').removeClass('selected');
            $(this).addClass('selected');
            $('#spu_optin_theme').val(new_theme);

            //update editor
            $editor.alterClass('spu-theme-*', 'spu-theme-'+ new_theme )

            if( new_theme == 'bar' ){
                $('#spu_position').val('top-bar').change();
            } else {
                $('#spu_position').val('centered').change();
            }
            // change default themes appeareance when we change themes
            $('#spu-border-width').val('');
            $('#spu-color').val('').change();
            $('#spu-background-color').val('').change();
            $('#spu-border-color').val('').change();
            $('#spu-cta-bg2').val('').change();
            $('#spu-button-bg').val('').change();
            $('#spu-button-color').val('').change();

            if( new_theme == 'cta' ) {
                $('.spu-cta_bg2').show();
            } else {
                $('.spu-cta_bg2').hide();
            }
        });

        /**
         * Integrations page
         */
        $('.toggle-provider').click(function(e){
            e.preventDefault();
            $(this).closest('.collapse-div').toggleClass('active');
        });
	});


	function checkTriggerMethodP( val ){
		if( val == 'trigger-click' || val == 'visible' ) {

			$(".spu-trigger-number").hide();
			$(".spu-trigger-value").fadeIn();

		} else if( val == 'exit-intent') {
			$(".spu-trigger-number").hide();
			$(".spu-trigger-value").hide();
            
        } else {

			$(".spu-trigger-value").hide();
			$(".spu-trigger-number").fadeIn();

		}
	}

    function getPxValue($el, retval)
    {
        if($el.val()) {
            return parseInt($el.val());
        } else {
            return (retval !== undefined) ? retval + "px" : 0;
        }
    }

    function getColor($el, retval)
    {
        if($el.val().length > 0) {
            return $el.wpColorPicker('color');
        } else {
            return (retval !== undefined) ? retval : '';
        }
    }

    function applyStyles()
    {
        var $editor = $("#content_ifr").contents().find('html');
        $editor.trigger('spu_tinymce_init');
        $editor.css({
            'background': '#9C9B9B;'
        });

        // if there is no optin free version will come into play
        if ( $('#spu_optin').val() != '') {
            // apply first as attr style to make !important work due to themes rules
            $editor.find("#tinymce").attr('style', 'border: ' + getPxValue($("#spu-border-width")) + "px solid " + getColor($("#spu-border-color")) + " !important");
            $editor.find("#tinymce").css({
                'background-color': getColor($("#spu-background-color")),
                'color': getColor($("#spu-color"))
            });
            $editor.find("button.spu-submit").css({
                'background-color': getColor($("#spu-button-bg")),
                'border-color': getColor($("#spu-button-bg")),
                'color': getColor($("#spu-button-color"))
            });
            $editor.find(".spu-fields-container").css({
                'background-color': getColor($("#spu-cta-bg2")),
                'border-color': getColor($("#spu-cta-bg2"))
            });
        }
    }
    /**
     * When tinyMcr loads
     */
    function TinyMceOptin() {
        SPUP_ADMIN.spu_editor = $("#content_ifr").contents().find('html #tinymce');
        var spu_box_container = false;
        // If there is not content add some
        var content = SPUP_ADMIN.spu_editor.text();
        if( SPUP_ADMIN.spu_editor.find('.spu-box-container').length) {
            spu_box_container = true;
            content = SPUP_ADMIN.spu_editor.html();
        }

        if( content == '' ){
            SPUP_ADMIN.spu_editor.html('<h2 style="text-align:center">Support us!</h2><p style="text-align:center">Subscribe to get the latest offers and deals!</p>')
        }
        //Add popup class if not exist
        if( !spu_box_container ) {
            SPUP_ADMIN.spu_editor.find('*').wrapAll('<div class="spu-box-container"/>');
        }
        // If we are using optin we need to add email field to form
        if (spup_js.opts.optin && spup_js.opts.optin != 'custom') {
            add_optin_form();
        }
        // add position class
        SPUP_ADMIN.spu_editor.addClass(' spu-position-' + spup_js.opts.css.position).removeClass('wp-autoresize');
        applyStyles();
    }

    function add_optin_form(){
        var $editor = SPUP_ADMIN.spu_editor,
            email_field = '<input type="email" name="spu-email" class="spu-fields spu-email" placeholder="'+$("#spu_optin_placeholder").val()+'"/>',
            name_field = '<input type="text" name="spu-name" class="spu-fields spu-name" placeholder="'+$("#spu_optin_name_placeholder").val()+'"/>',
            submit_btn = '<button type="submit" class="spu-fields spu-submit">'+$("#spu_optin_submit").val()+'<i class="spu-icon-spinner spu-spinner"></i></button>',
            $html = '<div class="spu-fields-container">' +
                '<form id="spu-optin-form" class="spu-optin-form" action="" method="post">' +
                '<input type="text" name="email" class="spu-helper-fields"/>' +
                '<input type="text" name="web" class="spu-helper-fields"/>';


        $editor.addClass('spu-optin-editor spu-theme-' + spup_js.opts.optin_theme + ' spu-position-' + spup_js.opts.css.position).removeClass('wp-autoresize');
        $editor.find(".spu-fields-container").remove();

        $('.spu-box-width').hide();

        if (spup_js.opts.optin_display_name == '1') {
            $html += name_field;
            $editor.addClass('with-spu-name');
        }
        $html += email_field;
        $html += submit_btn;
        $html += '</form></div>';
        if( $editor.hasClass( 'spu-position-top-bar') || $editor.hasClass( 'spu-position-bottom-bar') ) {
            $($html).appendTo($editor.find('.spu-box-container > p'));
        } else {
            $($html).appendTo($editor.find('.spu-box-container'));
        }
    }
    function init_color_picker() {
        // Apply styles to optins
        var color_field = $('#spu-appearance input.spu-color-field');
        if (color_field.length) {
            color_field.wpColorPicker({ change: applyStyles, clear: applyStyles });
        }
    }
    function remove_optin_form(){
        var $editor = $("#content_ifr").contents().find('html #tinymce');
        $editor.find(".spu-fields-container").remove();
        $editor.alterClass('spu-theme-*', '');
    }
    return {
        onTinyMceInit: function() {
            TinyMceOptin();
        }
    }
	
}(jQuery));
/**
 * jQuery alterClass plugin
 *
 * Remove element classes with wildcard matching. Optionally add classes:
 *   $( '#foo' ).alterClass( 'foo-* bar-*', 'foobar' )
 *
 * Copyright (c) 2011 Pete Boere (the-echoplex.net)
 * Free under terms of the MIT license: http://www.opensource.org/licenses/mit-license.php
 *
 */
(function ( $ ) {

    $.fn.alterClass = function ( removals, additions ) {

        var self = this;

        if ( removals.indexOf( '*' ) === -1 ) {
            // Use native jQuery methods if there is no wildcard matching
            self.removeClass( removals );
            return !additions ? self : self.addClass( additions );
        }

        var patt = new RegExp( '\\s' +
        removals.
            replace( /\*/g, '[A-Za-z0-9-_]+' ).
            split( ' ' ).
            join( '\\s|\\s' ) +
        '\\s', 'g' );

        self.each( function ( i, it ) {
            var cn = ' ' + it.className + ' ';
            while ( patt.test( cn ) ) {
                cn = cn.replace( patt, ' ' );
            }
            it.className = $.trim( cn );
        });

        return !additions ? self : self.addClass( additions );
    };

})( jQuery );
