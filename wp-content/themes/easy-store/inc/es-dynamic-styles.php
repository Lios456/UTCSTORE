<?php
/**
 * Managed the theme's dynamic styles.
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.2.0
 *
 */

/*---------------------- Custom CSS ------------------------------*/
    
    if ( ! function_exists( 'easy_store_custom_css' ) ) :

        /**
         * function to handle easy_store_head_css filter where all the css relation functions are hooked.
         *
         * @since 1.2.0
         */
        function easy_store_custom_css( $output_css = NULL ) {

            // Add filter easy_store_head_css for adding custom css via other functions.
            $output_css = apply_filters( 'easy_store_head_css', $output_css );

            if ( ! empty( $output_css ) ) {
                $output_css = wp_strip_all_tags( easy_store_minify_css( $output_css ) );
                echo "<!--Easy Store CSS -->\n<style type=\"text/css\">\n". $output_css ."\n</style>";
            }
        }

    endif;

    add_action( 'wp_head', 'easy_store_custom_css', 9999 );

/*---------------------- General CSS -----------------------------*/
	
	if ( ! function_exists( 'easy_store_general_css' ) ) :

        /**
         * function to handle the genral css for all sections.
         * 
         * @since 1.2.0
         */
        function easy_store_general_css( $output_css ) {

        	$easy_store_primary_theme_color   = easy_store_get_customizer_option_value( 'easy_store_primary_theme_color' );
        	$easy_store_secondary_theme_color = easy_store_get_customizer_option_value( 'easy_store_secondary_theme_color' );

        	//define variable for custom css
        	$custom_css = '';

	        $custom_css .= ".edit-link .post-edit-link,.reply .comment-reply-link,.widget_search .search-submit,.widget_search .search-submit,.woocommerce .price-cart:after,.woocommerce ul.products li.product .price-cart .button:hover,.woocommerce .widget_price_filter .ui-slider .ui-slider-range,.woocommerce .widget_price_filter .ui-slider .ui-slider-handle,.woocommerce .widget_price_filter .price_slider_wrapper .ui-widget-content,.woocommerce #respond input#submit:hover,.woocommerce a.button:hover,.woocommerce button.button:hover,.woocommerce input.button:hover,.woocommerce #respond input#submit.alt:hover,.woocommerce a.button.alt:hover,.woocommerce button.button.alt:hover,.woocommerce input.button.alt:hover,.woocommerce .added_to_cart.wc-forward:hover,.woocommerce ul.products li.product .onsale, .woocommerce span.onsale,.woocommerce #respond input#submit.alt.disabled,.woocommerce #respond input#submit.alt.disabled:hover,.woocommerce #respond input#submit.alt:disabled,.woocommerce #respond input#submit.alt:disabled:hover,.woocommerce #respond input#submit.alt[disabled]:disabled,.woocommerce #respond input#submit.alt[disabled]:disabled:hover,.woocommerce a.button.alt.disabled,.woocommerce a.button.alt.disabled:hover,.woocommerce a.button.alt:disabled,.woocommerce a.button.alt:disabled:hover,.woocommerce a.button.alt[disabled]:disabled,.woocommerce a.button.alt[disabled]:disabled:hover,.woocommerce button.button.alt.disabled,.woocommerce button.button.alt.disabled:hover,.woocommerce button.button.alt:disabled,.woocommerce button.button.alt:disabled:hover,.woocommerce button.button.alt[disabled]:disabled,.woocommerce button.button.alt[disabled]:disabled:hover,.woocommerce input.button.alt.disabled,.woocommerce input.button.alt.disabled:hover,.woocommerce input.button.alt:disabled,.woocommerce input.button.alt:disabled:hover,.woocommerce input.button.alt[disabled]:disabled,.woocommerce input.button.alt[disabled]:disabled:hover,.woocommerce-info, .woocommerce-noreviews, p.no-comments,#masthead .site-header-cart .cart-con.tents:hover,.es-main-menu-wrapper .mt-container,#site-navigation ul.sub-menu,#site-navigation ul.children,.easy_store_slider .es-slide-btn a:hover,.woocommerce-active .es-product-buttons-wrap a:hover,.woocommerce-active ul.products li.product .button:hover,.easy_store_testimonials .es-single-wrap .image-holder::after,.easy_store_testimonials .lSSlideOuter .lSPager.lSpg > li:hover a,.easy_store_testimonials .lSSlideOuter .lSPager.lSpg > li.active a,.cta-btn-wrap a,.main-post-wrap .post-date-wrap,.list-posts-wrap .post-date-wrap,.entry-content-wrapper .post-date-wrap,.widget .tagcloud a:hover,#es-scrollup,.easy_store_social_media a,.is-sticky .es-main-menu-wrapper, #masthead .site-header-cart .cart-contents:hover,.woocommerce-store-notice.demo_store,.wp-block-search .wp-block-search__button:hover,.widget_tag_cloud .tagcloud a:hover,.widget.widget_tag_cloud a:hover{ background: ". esc_attr( $easy_store_primary_theme_color ) ."}\n";
	        
	        $custom_css .= "a,.entry-footer a:hover,.comment-author .fn .url:hover,.commentmetadata .comment-edit-link,#cancel-comment-reply-link,#cancel-comment-reply-link:before,.logged-in-as a,.widget a:hover,.widget a:hover::before,.widget li:hover::before,.woocommerce .woocommerce-message:before,.woocommerce div.product p.price ins,.woocommerce div.product span.price ins,.woocommerce div.product p.price del,.woocommerce .woocommerce-info:before,.woocommerce .star-rating span::before,.woocommerce-account .woocommerce .woocommerce-MyAccount-navigation ul a:hover,.woocommerce-account .woocommerce .woocommerce-MyAccount-navigation ul li.is-active a:hover,.es-top-header-wrap .item-icon,.promo-items-wrapper .item-icon-wrap,.main-post-wrap .blog-content-wrapper .news-title a:hover,.list-posts-wrap .blog-content-wrapper .news-title a:hover,.entry-content-wrapper .entry-title a:hover,.blog-content-wrapper .post-meta span:hover, .blog-content-wrapper .post-meta span a:hover,.entry-content-wrapper .post-meta span:hover,.entry-content-wrapper .post-meta span a:hover,#footer-navigation ul li a:hover,.custom-header .breadcrumb-trail.breadcrumbs ul li a,.es-product-title-wrap a:hover .woocommerce-loop-product__title,.woocommerce-account .woocommerce .woocommerce-MyAccount-navigation ul .is-active a,.loginout{ color: ". esc_attr( $easy_store_primary_theme_color ) ."}\n";
	        
	        $custom_css .= ".navigation .nav-links a,.bttn,button,input[type='button'],input[type='reset'],input[type='submit'],.widget_search .search-submit,.woocommerce form .form-row.woocommerce-validated .select2-container,.woocommerce form .form-row.woocommerce-validated input.input-text,.woocommerce form .form-row.woocommerce-validated select,.tagcloud a:hover,.widget_tag_cloud .tagcloud a:hover,.widget.widget_tag_cloud a:hover { border-color: ". esc_attr( $easy_store_primary_theme_color ) ."}\n";
	        
	        $custom_css .= ".comment-list .comment-body { border-top-color: ". esc_attr( $easy_store_primary_theme_color ) ."}\n";
	        
	        $custom_css .= "@media (max-width: 768px) {.es-main-menu-wrapper #site-navigation { background: ". esc_attr( $easy_store_primary_theme_color ) ."}}\n";
	        
	        $custom_css .= ".navigation .nav-links a:hover,.bttn:hover,button,input[type='button']:hover,input[type='reset']:hover,input[type='submit']:hover,.home .es-home-icon a,.es-home-icon a:hover,#site-navigation ul li.current-menu-item>a,#site-navigation ul li:hover>a,#site-navigation ul li.current_page_ancestor>a,#site-navigation ul li.current_page_item>a,#site-navigation ul li.current-menu-ancestor>a,#site-navigation ul li.focus>a,.es-wishlist-btn,.es-slide-btn a,.es-slider-section .lSAction a:hover,.easy_store_featured_products .carousel-nav-action .carousel-controls:hover,.woocommerce span.onsale, .woocommerce ul.products li.product .onsale,.es-product-buttons-wrap a.add_to_wishlist:hover,.easy_store_call_to_action .cta-btn-wrap a:hover,.easy_store_social_media a:hover,.single-product .add_to_wishlist.single_add_to_wishlist,body:not(.woocommerce-block-theme-has-button-styles) .wc-block-components-button:not(.is-link):hover  { background: ". esc_attr( $easy_store_secondary_theme_color ) ."}\n";  

	        $custom_css .= "a:hover,a:focus,a:active,.woocommerce .price_label,.woocommerce.single-product div.product .price,.easy_store_advance_product_search .woocommerce-product-search .searchsubmit:hover,.price,.woocommerce ul.products li.product .price,.easy_store_categories_collection .es-coll-link,.easy_store_testimonials .es-single-wrap .post-author,.cta-content span,.custom-header .breadcrumb-trail.breadcrumbs ul li a:hover,.loginout:hover{ color: ". esc_attr( $easy_store_secondary_theme_color ) ."}\n";
	        
	        $custom_css .= ".navigation .nav-links a:hover,.bttn:hover,button,input[type='button']:hover,input[type='reset']:hover,input[type='submit']:hover,.easy_store_featured_products .carousel-nav-action .carousel-controls:hover{ border-color: ". esc_attr( $easy_store_secondary_theme_color ) ."}\n";
	        
	        $custom_css .= "@media (max-width: 768px) {.es-main-menu-wrapper .menu-toggle:hover { background: ". esc_attr( $easy_store_secondary_theme_color ) ."}}\n";
	        
	        $custom_css .= "#es-scrollup{ border-bottom-color: ". esc_attr( $easy_store_secondary_theme_color ) ."}\n";


	        if ( ! empty( $custom_css ) ) {
                $output_css .= $custom_css;
            }

            return $output_css;
            
        }

    endif;

    add_filter( 'easy_store_head_css', 'easy_store_general_css' );