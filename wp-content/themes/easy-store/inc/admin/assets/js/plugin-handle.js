/**
 * Get Started button on dashboard notice.
 */

jQuery(document).ready(function($) {
    var WpAjaxurl = esAdminObject.ajax_url;
    var _wpnonce = esAdminObject._wpnonce;
    var buttonStatus = esAdminObject.buttonStatus;

    /**
     * Popup on click demo import if mysterythemes demo importer plugin is not activated.
     */
    if( buttonStatus === 'disable' ) $( '.easy-store-demo-import' ).addClass( 'disabled' );

    switch( buttonStatus ) {
        case 'activate' :
            $( '.easy-store-get-started' ).on( 'click', function() {
                var _this = $( this );
                easy_store_do_plugin( 'easy_store_activate_plugin', _this );
            });
            $( '.easy-store-activate-demo-import-plugin' ).on( 'click', function() {
                var _this = $( this );
                easy_store_do_plugin( 'easy_store_activate_plugin', _this );
            });
            break;
        case 'install' :
            $( '.easy-store-get-started' ).on( 'click', function() {
                var _this = $( this );
                easy_store_do_plugin( 'easy_store_install_plugin', _this );
            });
            $( '.easy-store-install-demo-import-plugin' ).on( 'click', function() {
                var _this = $( this );
                easy_store_do_plugin( 'easy_store_install_plugin', _this );
            });
            break;
        case 'redirect' :
            $( '.easy-store-get-started' ).on( 'click', function() {
                var _this = $( this );
                location.href = _this.data( 'redirect' );
            });
            break;
    }
    
    easy_store_do_plugin = function ( ajax_action, _this ) {
        $.ajax({
            method : "POST",
            url : WpAjaxurl,
            data : ({
                'action' : ajax_action,
                '_wpnonce' : _wpnonce
            }),
            beforeSend: function() {
                var loadingTxt = _this.data( 'process' );
                _this.addClass( 'updating-message' ).text( loadingTxt );
            },
            success: function( response ) {
                if( response.success ) {
                    var loadedTxt = _this.data( 'done' );
                    _this.removeClass( 'updating-message' ).text( loadedTxt );
                }
                location.href = _this.data( 'redirect' );
            }
        });
    }

    $('.es-plugin-action').on('click', function(e){
        e.preventDefault();
        var _this = $( this ), btnAction = $(this).data('action'), pluginSlug = $(this).data('slug'), pluginFile = $(this).data('file');
        
        switch( btnAction ) {
            case 'activate' :
                easy_store_do_free_plugin( 'easy_store_activate_free_plugin', _this, pluginSlug, pluginFile );
                break;
            case 'install' :
                easy_store_do_free_plugin( 'easy_store_install_free_plugin', _this, pluginSlug, pluginFile );
                break;
        }
    });

    easy_store_do_free_plugin = function ( ajax_action, _this, slug, file ) {
        $.ajax({
            method : "POST",
            url : WpAjaxurl,
            data : ({
                'action'    : ajax_action,
                '_wpnonce'  : _wpnonce,
                'plugin'    : slug,
                'file'      : file
            }),
            beforeSend: function() {
                var loadingTxt = _this.data( 'process' );
                _this.addClass( 'updating-message' ).text( loadingTxt );
            },
            success: function( response ) {
                if( response.success ) {
                    var loadedTxt = _this.data( 'done' );
                    _this.removeClass( 'updating-message' ).text( loadedTxt );
                }
                location.href = _this.data( 'redirect' );
            }
        });
    }

});