<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

/*--------------------- Custom body classes ------------------------*/
    /**
     * Adds custom classes to the array of body classes.
     *
     * @param array $classes Classes for the body element.
     * @return array
     */
    function easy_store_body_classes( $classes ) {

        global $post;

        // Adds a class of hfeed to non-singular pages.
        if ( ! is_singular() ) {
            $classes[] = 'hfeed';
        }

        /**
         * Sidebar option for post/page/archive
         *
         * @since 1.0.0
         */
        if ( 'post' === get_post_type() ) {
            $sidebar_meta_option = get_post_meta( $post->ID, 'easy_store_sidebar_layout', true );
        }

        if ( 'page' === get_post_type() ) {
            $sidebar_meta_option = get_post_meta( $post->ID, 'easy_store_sidebar_layout', true );
        }
         
        if ( is_home() ) {
            $home_id = get_option( 'page_for_posts' );
            $sidebar_meta_option = get_post_meta( $home_id, 'easy_store_sidebar_layout', true );
        }
        
        if ( empty( $sidebar_meta_option ) || is_archive() || is_search() ) {
            $sidebar_meta_option = 'default_sidebar';
        }

        if ( 'product' === get_post_type() ) {
            $page_id = wc_get_page_id( 'shop' );
            $sidebar_meta_option = get_post_meta( $page_id, 'easy_store_sidebar_layout', true );
        }

        $archive_sidebar        = easy_store_get_customizer_option_value( 'easy_store_archive_sidebar' );
        $post_default_sidebar   = easy_store_get_customizer_option_value( 'easy_store_global_post_sidebar' );
        $page_default_sidebar   = easy_store_get_customizer_option_value( 'easy_store_global_page_sidebar' );
        
        if ( $sidebar_meta_option == 'default_sidebar' ) {
            if ( is_single() ) {
                if ( $post_default_sidebar == 'right_sidebar' ) {
                    $classes[] = 'right-sidebar';
                } elseif ( $post_default_sidebar == 'left_sidebar' ) {
                    $classes[] = 'left-sidebar';
                } elseif ( $post_default_sidebar == 'no_sidebar' ) {
                    $classes[] = 'no-sidebar';
                } elseif ( $post_default_sidebar == 'no_sidebar_center' ) {
                    $classes[] = 'no-sidebar-center';
                }
            } elseif ( is_page() ) {
                if ( $page_default_sidebar == 'right_sidebar' ) {
                    $classes[] = 'right-sidebar';
                } elseif ( $page_default_sidebar == 'left_sidebar' ) {
                    $classes[] = 'left-sidebar';
                } elseif ( $page_default_sidebar == 'no_sidebar' ) {
                    $classes[] = 'no-sidebar';
                } elseif ( $page_default_sidebar == 'no_sidebar_center' ) {
                    $classes[] = 'no-sidebar-center';
                }
            } elseif ( $archive_sidebar == 'right_sidebar' ) {
                $classes[] = 'right-sidebar';
            } elseif ( $archive_sidebar == 'left_sidebar' ) {
                $classes[] = 'left-sidebar';
            } elseif ( $archive_sidebar == 'no_sidebar' ) {
                $classes[] = 'no-sidebar';
            } elseif ( $archive_sidebar == 'no_sidebar_center' ) {
                $classes[] = 'no-sidebar-center';
            }
        } elseif ( $sidebar_meta_option == 'right_sidebar' ) {
            $classes[] = 'right-sidebar';
        } elseif ( $sidebar_meta_option == 'left_sidebar' ) {
            $classes[] = 'left-sidebar';
        } elseif ( $sidebar_meta_option == 'no_sidebar' ) {
            $classes[] = 'no-sidebar';
        } elseif ( $sidebar_meta_option == 'no_sidebar_center' ) {
            $classes[] = 'no-sidebar-center';
        }
        
        $easy_store_site_layout = easy_store_get_customizer_option_value( 'easy_store_site_layout' );
        if ( !empty( $easy_store_site_layout ) ) {
            $classes[] = esc_attr( $easy_store_site_layout ).'-layout';
        }

        return $classes;
    }

    add_filter( 'body_class', 'easy_store_body_classes' );

/*--------------------- Ping back ----------------------------------*/
    /**
     * Add a pingback url auto-discovery header for singularly identifiable articles.
     */
    function easy_store_pingback_header() {
        if ( is_singular() && pings_open() ) {
            echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
        }
    }

    add_action( 'wp_head', 'easy_store_pingback_header' );

/*--------------------- Google Font --------------------------------*/
    /**
     * Register Google fonts for News Portal.
     *
     * @return string Google fonts URL for the theme.
     * @since 1.0.0
     */
    if ( ! function_exists( 'easy_store_fonts_url' ) ) :

        function easy_store_fonts_url() {
            $fonts_url = '';
            $font_families = array();

            /*
             * Translators: If there are characters in your language that are not supported
             * by Poppins, translate this to 'off'. Do not translate into your own language.
             */
            if ( 'off' !== _x( 'on', 'Poppins font: on or off', 'easy-store' ) ) {
                $font_families[] = 'Poppins:400,700,900';
            }

            if ( $font_families ) {
                $query_args = array(
                    'family' => urlencode( implode( '|', $font_families ) ),
                    'subset' => urlencode( 'latin,latin-ext' ),
                );

                $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
            }

            return $fonts_url;
        }

    endif;

/*--------------------- Enqueue style and scripts ------------------*/
    
    if ( ! function_exists( 'easy_store_admin_scripts_style' ) ) :

        /**
         * Enqueue Scripts and styles for admin
         *
         * @since 1.0.0
         */
        function easy_store_admin_scripts_style( $hook ) {

            if ( 'widgets.php' != $hook && 'edit.php' != $hook && 'post.php' != $hook && 'post-new.php' != $hook ) {
                return;
            }

            if ( function_exists( 'wp_enqueue_media' ) ) {
                wp_enqueue_media();
            }

            wp_enqueue_script( 'jquery-ui-button' );

            wp_enqueue_style( 'easy-store-admin-style', get_template_directory_uri() .'/assets/css/es-admin-styles.css', array(), EASY_STORE_VERSION );

            wp_enqueue_script( 'easy-store-admin-script', get_template_directory_uri() .'/assets/js/es-admin-scripts.js', array('jquery'), EASY_STORE_VERSION, true );
        }

    endif;

    add_action( 'admin_enqueue_scripts', 'easy_store_admin_scripts_style' );

    if ( ! function_exists( 'easy_store_scripts' ) ) :

        /**
         * Enqueue scripts and styles.
         */
        function easy_store_scripts() {

            wp_enqueue_style( 'easy-store-fonts', easy_store_fonts_url(), array(), null );

            wp_enqueue_style( 'lightslider-styles', get_template_directory_uri() .'/assets/library/lightslider/css/lightslider.css', array(), '1.1.6' );

            wp_enqueue_style( 'custom-scrollbar-styles', get_template_directory_uri() .'/assets/library/custom-scrollbar/jquery.mCustomScrollbar.min.css', array(), '1.0.0' );

            wp_enqueue_style( 'font-awesome', get_template_directory_uri() .'/assets/library/font-awesome/css/font-awesome.min.css', array(), '4.7.0' );

            wp_enqueue_style( 'easy-store-style', get_stylesheet_uri(), array(), EASY_STORE_VERSION );
            
            wp_enqueue_style( 'easy-store-responsive-style', get_template_directory_uri() .'/assets/css/es-responsive.css', array(), '1.0.0' );

            wp_enqueue_script( 'easy-store-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), EASY_STORE_VERSION, true );
            
            wp_enqueue_script( 'easy-store-combine-scripts', get_template_directory_uri() .'/assets/js/es-combine-scripts.js', array( 'jquery' ), EASY_STORE_VERSION, true );
            
            wp_enqueue_script( 'easy-store-sticky-sidebar-scripts', get_template_directory_uri() .'/assets/library/stickysidebar/theia-sticky-sidebar.js', array( 'jquery' ), EASY_STORE_VERSION, true );
            
            $easy_store_primary_menu_sticky = easy_store_get_customizer_option_value( 'easy_store_primary_menu_sticky' );
            
            if ( true == $easy_store_primary_menu_sticky ) {
                  wp_enqueue_script( 'jquery-sticky', get_template_directory_uri(). '/assets/library/sticky/jquery.sticky.js', array( 'jquery' ), '20150416', true );
            
                  wp_enqueue_script( 'np-sticky-menu-setting', get_template_directory_uri(). '/assets/library/sticky/sticky-setting.js', array( 'jquery-sticky' ), '20150309', true );
            }

            wp_register_script( 'easy-store-custom-script', get_template_directory_uri() .'/assets/js/es-custom-scripts.js', array( 'jquery' ), EASY_STORE_VERSION, true );

            $store_sticky_option = easy_store_get_customizer_option_value( 'easy_store_general_sidebar_sticky_option' );

            $store_sticky_option = ( true == $store_sticky_option ) ? 'show' : 'hide';

            wp_localize_script( 'easy-store-custom-script', 'mtObject', array(
                'store_sticky'  => $store_sticky_option,
            ) );
            
            wp_enqueue_script( 'easy-store-custom-script' );
            
            if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
                wp_enqueue_script( 'comment-reply' );
            }
        }

    endif;

    add_action( 'wp_enqueue_scripts', 'easy_store_scripts' );

/*--------------------- Font awesome icon array --------------------*/

    if ( ! function_exists( 'easy_store_font_awesome_icon_array' ) ) :

        /**
         * Define font awesome icons
         *
         * @return array();
         * @since 1.0.0
         */
        function easy_store_font_awesome_icon_array() {

            return array("fa fa-glass","fa fa-music","fa fa-search","fa fa-envelope-o","fa fa-heart","fa fa-star","fa fa-star-o","fa fa-user","fa fa-film","fa fa-th-large","fa fa-th","fa fa-th-list","fa fa-check","fa fa-remove","fa fa-close","fa fa-times","fa fa-search-plus","fa fa-search-minus","fa fa-power-off","fa fa-signal","fa fa-gear","fa fa-cog","fa fa-trash-o","fa fa-home","fa fa-file-o","fa fa-clock-o","fa fa-road","fa fa-download","fa fa-arrow-circle-o-down","fa fa-arrow-circle-o-up","fa fa-inbox","fa fa-play-circle-o","fa fa-rotate-right","fa fa-repeat","fa fa-refresh","fa fa-list-alt","fa fa-lock","fa fa-flag","fa fa-headphones","fa fa-volume-off","fa fa-volume-down","fa fa-volume-up","fa fa-qrcode","fa fa-barcode","fa fa-tag","fa fa-tags","fa fa-book","fa fa-bookmark","fa fa-print","fa fa-camera","fa fa-font","fa fa-bold","fa fa-italic","fa fa-text-height","fa fa-text-width","fa fa-align-left","fa fa-align-center","fa fa-align-right","fa fa-align-justify","fa fa-list","fa fa-dedent","fa fa-outdent","fa fa-indent","fa fa-video-camera","fa fa-photo","fa fa-image","fa fa-picture-o","fa fa-pencil","fa fa-map-marker","fa fa-adjust","fa fa-tint","fa fa-edit","fa fa-pencil-square-o","fa fa-share-square-o","fa fa-check-square-o","fa fa-arrows","fa fa-step-backward","fa fa-fast-backward","fa fa-backward","fa fa-play","fa fa-pause","fa fa-stop","fa fa-forward","fa fa-fast-forward","fa fa-step-forward","fa fa-eject","fa fa-chevron-left","fa fa-chevron-right","fa fa-plus-circle","fa fa-minus-circle","fa fa-times-circle","fa fa-check-circle","fa fa-question-circle","fa fa-info-circle","fa fa-crosshairs","fa fa-times-circle-o","fa fa-check-circle-o","fa fa-ban","fa fa-arrow-left","fa fa-arrow-right","fa fa-arrow-up","fa fa-arrow-down","fa fa-mail-forward","fa fa-share","fa fa-expand","fa fa-compress","fa fa-plus","fa fa-minus","fa fa-asterisk","fa fa-exclamation-circle","fa fa-gift","fa fa-leaf","fa fa-fire","fa fa-eye","fa fa-eye-slash","fa fa-warning","fa fa-exclamation-triangle","fa fa-plane","fa fa-calendar","fa fa-random","fa fa-comment","fa fa-magnet","fa fa-chevron-up","fa fa-chevron-down","fa fa-retweet","fa fa-shopping-cart","fa fa-folder","fa fa-folder-open","fa fa-arrows-v","fa fa-arrows-h","fa fa-bar-chart-o","fa fa-bar-chart","fa fa-twitter-square","fa fa-facebook-square","fa fa-camera-retro","fa fa-key","fa fa-gears","fa fa-cogs","fa fa-comments","fa fa-thumbs-o-up","fa fa-thumbs-o-down","fa fa-star-half","fa fa-heart-o","fa fa-sign-out","fa fa-linkedin-square","fa fa-thumb-tack","fa fa-external-link","fa fa-sign-in","fa fa-trophy","fa fa-github-square","fa fa-upload","fa fa-lemon-o","fa fa-phone","fa fa-square-o","fa fa-bookmark-o","fa fa-phone-square","fa fa-twitter","fa fa-facebook-f","fa fa-facebook","fa fa-github","fa fa-unlock","fa fa-credit-card","fa fa-feed","fa fa-rss","fa fa-hdd-o","fa fa-bullhorn","fa fa-bell","fa fa-certificate","fa fa-hand-o-right","fa fa-hand-o-left","fa fa-hand-o-up","fa fa-hand-o-down","fa fa-arrow-circle-left","fa fa-arrow-circle-right","fa fa-arrow-circle-up","fa fa-arrow-circle-down","fa fa-globe","fa fa-wrench","fa fa-tasks","fa fa-filter","fa fa-briefcase","fa fa-arrows-alt","fa fa-group","fa fa-users","fa fa-chain","fa fa-link","fa fa-cloud","fa fa-flask","fa fa-cut","fa fa-scissors","fa fa-copy","fa fa-files-o","fa fa-paperclip","fa fa-save","fa fa-floppy-o","fa fa-square","fa fa-navicon","fa fa-reorder","fa fa-bars","fa fa-list-ul","fa fa-list-ol","fa fa-strikethrough","fa fa-underline","fa fa-table","fa fa-magic","fa fa-truck","fa fa-pinterest","fa fa-pinterest-square","fa fa-google-plus-square","fa fa-google-plus","fa fa-money","fa fa-caret-down","fa fa-caret-up","fa fa-caret-left","fa fa-caret-right","fa fa-columns","fa fa-unsorted","fa fa-sort","fa fa-sort-down","fa fa-sort-desc","fa fa-sort-up","fa fa-sort-asc","fa fa-envelope","fa fa-linkedin","fa fa-rotate-left","fa fa-undo","fa fa-legal","fa fa-gavel","fa fa-dashboard","fa fa-tachometer","fa fa-comment-o","fa fa-comments-o","fa fa-flash","fa fa-bolt","fa fa-sitemap","fa fa-umbrella","fa fa-paste","fa fa-clipboard","fa fa-lightbulb-o","fa fa-exchange","fa fa-cloud-download","fa fa-cloud-upload","fa fa-user-md","fa fa-stethoscope","fa fa-suitcase","fa fa-bell-o","fa fa-coffee","fa fa-cutlery","fa fa-file-text-o","fa fa-building-o","fa fa-hospital-o","fa fa-ambulance","fa fa-medkit","fa fa-fighter-jet","fa fa-beer","fa fa-h-square","fa fa-plus-square","fa fa-angle-double-left","fa fa-angle-double-right","fa fa-angle-double-up","fa fa-angle-double-down","fa fa-angle-left","fa fa-angle-right","fa fa-angle-up","fa fa-angle-down","fa fa-desktop","fa fa-laptop","fa fa-tablet","fa fa-mobile-phone","fa fa-mobile","fa fa-circle-o","fa fa-quote-left","fa fa-quote-right","fa fa-spinner","fa fa-circle","fa fa-mail-reply","fa fa-reply","fa fa-github-alt","fa fa-folder-o","fa fa-folder-open-o","fa fa-smile-o","fa fa-frown-o","fa fa-meh-o","fa fa-gamepad","fa fa-keyboard-o","fa fa-flag-o","fa fa-flag-checkered","fa fa-terminal","fa fa-code","fa fa-mail-reply-all","fa fa-reply-all","fa fa-star-half-empty","fa fa-star-half-full","fa fa-star-half-o","fa fa-location-arrow","fa fa-crop","fa fa-code-fork","fa fa-unlink","fa fa-chain-broken","fa fa-question","fa fa-info","fa fa-exclamation","fa fa-superscript","fa fa-subscript","fa fa-eraser","fa fa-puzzle-piece","fa fa-microphone","fa fa-microphone-slash","fa fa-shield","fa fa-calendar-o","fa fa-fire-extinguisher","fa fa-rocket","fa fa-maxcdn","fa fa-chevron-circle-left","fa fa-chevron-circle-right","fa fa-chevron-circle-up","fa fa-chevron-circle-down","fa fa-html5","fa fa-css3","fa fa-anchor","fa fa-unlock-alt","fa fa-bullseye","fa fa-ellipsis-h","fa fa-ellipsis-v","fa fa-rss-square","fa fa-play-circle","fa fa-ticket","fa fa-minus-square","fa fa-minus-square-o","fa fa-level-up","fa fa-level-down","fa fa-check-square","fa fa-pencil-square","fa fa-external-link-square","fa fa-share-square","fa fa-compass","fa fa-toggle-down","fa fa-caret-square-o-down","fa fa-toggle-up","fa fa-caret-square-o-up","fa fa-toggle-right","fa fa-caret-square-o-right","fa fa-euro","fa fa-eur","fa fa-gbp","fa fa-dollar","fa fa-usd","fa fa-rupee","fa fa-inr","fa fa-cny","fa fa-rmb","fa fa-yen","fa fa-jpy","fa fa-ruble","fa fa-rouble","fa fa-rub","fa fa-won","fa fa-krw","fa fa-bitcoin","fa fa-btc","fa fa-file","fa fa-file-text","fa fa-sort-alpha-asc","fa fa-sort-alpha-desc","fa fa-sort-amount-asc","fa fa-sort-amount-desc","fa fa-sort-numeric-asc","fa fa-sort-numeric-desc","fa fa-thumbs-up","fa fa-thumbs-down","fa fa-youtube-square","fa fa-youtube","fa fa-xing","fa fa-xing-square","fa fa-youtube-play","fa fa-dropbox","fa fa-stack-overflow","fa fa-instagram","fa fa-flickr","fa fa-adn","fa fa-bitbucket","fa fa-bitbucket-square","fa fa-tumblr","fa fa-tumblr-square","fa fa-long-arrow-down","fa fa-long-arrow-up","fa fa-long-arrow-left","fa fa-long-arrow-right","fa fa-apple","fa fa-windows","fa fa-android","fa fa-linux","fa fa-dribbble","fa fa-skype","fa fa-foursquare","fa fa-trello","fa fa-female","fa fa-male","fa fa-gittip","fa fa-gratipay","fa fa-sun-o","fa fa-moon-o","fa fa-archive","fa fa-bug","fa fa-vk","fa fa-weibo","fa fa-renren","fa fa-pagelines","fa fa-stack-exchange","fa fa-arrow-circle-o-right","fa fa-arrow-circle-o-left","fa fa-toggle-left","fa fa-caret-square-o-left","fa fa-dot-circle-o","fa fa-wheelchair","fa fa-vimeo-square","fa fa-turkish-lira","fa fa-try","fa fa-plus-square-o","fa fa-space-shuttle","fa fa-slack","fa fa-envelope-square","fa fa-wordpress","fa fa-openid","fa fa-institution","fa fa-bank","fa fa-university","fa fa-mortar-board","fa fa-graduation-cap","fa fa-yahoo","fa fa-google","fa fa-reddit","fa fa-reddit-square","fa fa-stumbleupon-circle","fa fa-stumbleupon","fa fa-delicious","fa fa-digg","fa fa-pied-piper-pp","fa fa-pied-piper-alt","fa fa-drupal","fa fa-joomla","fa fa-language","fa fa-fax","fa fa-building","fa fa-child","fa fa-paw","fa fa-spoon","fa fa-cube","fa fa-cubes","fa fa-behance","fa fa-behance-square","fa fa-steam","fa fa-steam-square","fa fa-recycle","fa fa-automobile","fa fa-car","fa fa-cab","fa fa-taxi","fa fa-tree","fa fa-spotify","fa fa-deviantart","fa fa-soundcloud","fa fa-database","fa fa-file-pdf-o","fa fa-file-word-o","fa fa-file-excel-o","fa fa-file-powerpoint-o","fa fa-file-photo-o","fa fa-file-picture-o","fa fa-file-image-o","fa fa-file-zip-o","fa fa-file-archive-o","fa fa-file-sound-o","fa fa-file-audio-o","fa fa-file-movie-o","fa fa-file-video-o","fa fa-file-code-o","fa fa-vine","fa fa-codepen","fa fa-jsfiddle","fa fa-life-bouy","fa fa-life-buoy","fa fa-life-saver","fa fa-support","fa fa-life-ring","fa fa-circle-o-notch","fa fa-ra","fa fa-resistance","fa fa-rebel","fa fa-ge","fa fa-empire","fa fa-git-square","fa fa-git","fa fa-y-combinator-square","fa fa-yc-square","fa fa-hacker-news","fa fa-tencent-weibo","fa fa-qq","fa fa-wechat","fa fa-weixin","fa fa-send","fa fa-paper-plane","fa fa-send-o","fa fa-paper-plane-o","fa fa-history","fa fa-circle-thin","fa fa-header","fa fa-paragraph","fa fa-sliders","fa fa-share-alt","fa fa-share-alt-square","fa fa-bomb","fa fa-soccer-ball-o","fa fa-futbol-o","fa fa-tty","fa fa-binoculars","fa fa-plug","fa fa-slideshare","fa fa-twitch","fa fa-yelp","fa fa-newspaper-o","fa fa-wifi","fa fa-calculator","fa fa-paypal","fa fa-google-wallet","fa fa-cc-visa","fa fa-cc-mastercard","fa fa-cc-discover","fa fa-cc-amex","fa fa-cc-paypal","fa fa-cc-stripe","fa fa-bell-slash","fa fa-bell-slash-o","fa fa-trash","fa fa-copyright","fa fa-at","fa fa-eyedropper","fa fa-paint-brush","fa fa-birthday-cake","fa fa-area-chart","fa fa-pie-chart","fa fa-line-chart","fa fa-lastfm","fa fa-lastfm-square","fa fa-toggle-off","fa fa-toggle-on","fa fa-bicycle","fa fa-bus","fa fa-ioxhost","fa fa-angellist","fa fa-cc","fa fa-shekel","fa fa-sheqel","fa fa-ils","fa fa-meanpath","fa fa-buysellads","fa fa-connectdevelop","fa fa-dashcube","fa fa-forumbee","fa fa-leanpub","fa fa-sellsy","fa fa-shirtsinbulk","fa fa-simplybuilt","fa fa-skyatlas","fa fa-cart-plus","fa fa-cart-arrow-down","fa fa-diamond","fa fa-ship","fa fa-user-secret","fa fa-motorcycle","fa fa-street-view","fa fa-heartbeat","fa fa-venus","fa fa-mars","fa fa-mercury","fa fa-intersex","fa fa-transgender","fa fa-transgender-alt","fa fa-venus-double","fa fa-mars-double","fa fa-venus-mars","fa fa-mars-stroke","fa fa-mars-stroke-v","fa fa-mars-stroke-h","fa fa-neuter","fa fa-genderless","fa fa-facebook-official","fa fa-pinterest-p","fa fa-whatsapp","fa fa-server","fa fa-user-plus","fa fa-user-times","fa fa-hotel","fa fa-bed","fa fa-viacoin","fa fa-train","fa fa-subway","fa fa-medium","fa fa-yc","fa fa-y-combinator","fa fa-optin-monster","fa fa-opencart","fa fa-expeditedssl","fa fa-battery-4","fa fa-battery-full","fa fa-battery-3","fa fa-battery-three-quarters","fa fa-battery-2","fa fa-battery-half","fa fa-battery-1","fa fa-battery-quarter","fa fa-battery-0","fa fa-battery-empty","fa fa-mouse-pointer","fa fa-i-cursor","fa fa-object-group","fa fa-object-ungroup","fa fa-sticky-note","fa fa-sticky-note-o","fa fa-cc-jcb","fa fa-cc-diners-club","fa fa-clone","fa fa-balance-scale","fa fa-hourglass-o","fa fa-hourglass-1","fa fa-hourglass-start","fa fa-hourglass-2","fa fa-hourglass-half","fa fa-hourglass-3","fa fa-hourglass-end","fa fa-hourglass","fa fa-hand-grab-o","fa fa-hand-rock-o","fa fa-hand-stop-o","fa fa-hand-paper-o","fa fa-hand-scissors-o","fa fa-hand-lizard-o","fa fa-hand-spock-o","fa fa-hand-pointer-o","fa fa-hand-peace-o","fa fa-trademark","fa fa-registered","fa fa-creative-commons","fa fa-gg","fa fa-gg-circle","fa fa-tripadvisor","fa fa-odnoklassniki","fa fa-odnoklassniki-square","fa fa-get-pocket","fa fa-wikipedia-w","fa fa-safari","fa fa-chrome","fa fa-firefox","fa fa-opera","fa fa-internet-explorer","fa fa-tv","fa fa-television","fa fa-contao","fa fa-500px","fa fa-amazon","fa fa-calendar-plus-o","fa fa-calendar-minus-o","fa fa-calendar-times-o","fa fa-calendar-check-o","fa fa-industry","fa fa-map-pin","fa fa-map-signs","fa fa-map-o","fa fa-map","fa fa-commenting","fa fa-commenting-o","fa fa-houzz","fa fa-vimeo","fa fa-black-tie","fa fa-fonticons","fa fa-reddit-alien","fa fa-edge","fa fa-credit-card-alt","fa fa-codiepie","fa fa-modx","fa fa-fort-awesome","fa fa-usb","fa fa-product-hunt","fa fa-mixcloud","fa fa-scribd","fa fa-pause-circle","fa fa-pause-circle-o","fa fa-stop-circle","fa fa-stop-circle-o","fa fa-shopping-bag","fa fa-shopping-basket","fa fa-hashtag","fa fa-bluetooth","fa fa-bluetooth-b","fa fa-percent","fa fa-gitlab","fa fa-wpbeginner","fa fa-wpforms","fa fa-envira","fa fa-universal-access","fa fa-wheelchair-alt","fa fa-question-circle-o","fa fa-blind","fa fa-audio-description","fa fa-volume-control-phone","fa fa-braille","fa fa-assistive-listening-systems","fa fa-asl-interpreting","fa fa-american-sign-language-interpreting","fa fa-deafness","fa fa-hard-of-hearing","fa fa-deaf","fa fa-glide","fa fa-glide-g","fa fa-signing","fa fa-sign-language","fa fa-low-vision","fa fa-viadeo","fa fa-viadeo-square","fa fa-snapchat","fa fa-snapchat-ghost","fa fa-snapchat-square","fa fa-pied-piper","fa fa-first-order","fa fa-yoast","fa fa-themeisle","fa fa-google-plus-circle","fa fa-google-plus-official","fa fa-fa","fa fa-font-awesome");
        }

    endif;

    if ( ! function_exists( 'easy_store_font_awesome_social_icon_array' ) ) :

        /**
         * Define font awesome social media icons
         *
         * @return array();
         * @since 1.0.0
         */
        function easy_store_font_awesome_social_icon_array() {
            return array(
                "fa fa-facebook-square","fa fa-facebook-f","fa fa-facebook","fa fa-facebook-official","fa fa-twitter-square","fa fa-twitter","fa fa-yahoo","fa fa-google","fa fa-google-wallet","fa fa-google-plus-circle","fa fa-google-plus-official","fa fa-instagram","fa fa-linkedin-square","fa fa-linkedin","fa fa-pinterest-p","fa fa-pinterest","fa fa-pinterest-square","fa fa-google-plus-square","fa fa-google-plus","fa fa-youtube-square","fa fa-youtube","fa fa-youtube-play","fa fa-vimeo","fa fa-vimeo-square",
            );
        }
        
    endif;

/*--------------------- Get categories list ------------------------*/

    if ( ! function_exists( 'easy_store_categories_lists' ) ) :

        /**
         * categories list
         *
         * @return array();
         */
        function easy_store_categories_lists() {
            $easy_store_cat_args = array(
                'type'        => 'post',
                'child_of'    => 0,
                'orderby'     => 'name',
                'order'       => 'ASC',
                'hide_empty'  => 1,
                'taxonomy'    => 'category',
            );
            $easy_store_categories = get_categories( $easy_store_cat_args );
            $easy_store_categories_lists = array();
            foreach( $easy_store_categories as $category ) {
                $easy_store_categories_lists[esc_attr( $category->slug )] = esc_html( $category->name );
            }
            return $easy_store_categories_lists;
        }
        
    endif;

/*--------------------- Get innerpage header bg image --------------*/

    if ( ! function_exists( 'easy_store_inner_header_bg_image' ) ) :

        /**
         * Background image for inner page header
         *
         * @since 1.0.0
         */
        function easy_store_inner_header_bg_image( $input ) {

            $image_attr = array();

            if ( empty( $image_attr ) ) {

                // Fetch from Custom Header Image.
                $image = get_header_image();
                if ( ! empty( $image ) ) {
                    $image_attr['url']    = $image;
                    $image_attr['width']  = get_custom_header()->width;
                    $image_attr['height'] = get_custom_header()->height;
                }
            }

            if ( ! empty( $image_attr ) ) {
                $input .= 'background-image:url(' . esc_url( $image_attr['url'] ) . ');';
                $input .= 'background-size:cover;';
            }

            return $input;
        }

    endif;

    add_filter( 'easy_store_inner_header_style_attribute', 'easy_store_inner_header_bg_image' );

/*--------------------- Breadcrumb for innerpages ------------------*/

    if ( ! function_exists( 'easy_store_inner_breadcrumb' ) ) :

        /**
         * Breadcrumb in innerpages
         *
         * @since 1.0.0
         */
        function easy_store_inner_breadcrumb() {

            $easy_store_breadcrumb_option = easy_store_get_customizer_option_value( 'easy_store_breadcrumb_option' );

            if ( false === $easy_store_breadcrumb_option ) {
                return;
            }

            if ( ! function_exists( 'breadcrumb_trail' ) ) {
                require_once get_template_directory() . '/inc/es-breadcrumbs.php';
            }

            $breadcrumb_args = array(
                'container'   => 'div',
                'show_browse' => false,
            );
            breadcrumb_trail( $breadcrumb_args );

        }

    endif;

/*--------------------- Update wishlist count with Ajax ------------*/

    if ( defined( 'YITH_WCWL' ) && ! function_exists( 'easy_store_yith_wcwl_ajax_update_count' ) ):

        /**
         * Update count for using ajax
         */
        function easy_store_yith_wcwl_ajax_update_count() {
            wp_send_json( array(
            'count' => yith_wcwl_count_all_products()
            ) );
        }

    endif;

    add_action( 'wp_ajax_easy_store_yith_wcwl_update_wishlist_count', 'easy_store_yith_wcwl_ajax_update_count' );
    add_action( 'wp_ajax_nopriv_easy_store_yith_wcwl_update_wishlist_count', 'easy_store_yith_wcwl_ajax_update_count' );

/*--------------------- Social media content -----------------------*/

    if ( ! function_exists( 'easy_store_social_media_content' ) ) :

        /**
         * Display content of social media as repeater field
         *
         * @since 1.0.0
         */
        function easy_store_social_media_content() {
            $get_social_media_icons = easy_store_get_customizer_option_value( 'social_media_icons' );
            $get_decode_social_media = json_decode( $get_social_media_icons );
            if ( !empty( $get_decode_social_media ) ) {
                echo '<div class="es-social-icons-wrapper">';
                foreach ( $get_decode_social_media as $single_icon ) {
                    $icon_class = $single_icon->mt_item_social_icon;
                    $icon_url = $single_icon->mt_item_url;
                    if ( !empty( $icon_url ) ) {
                        echo '<span class="social-link"><a href="'. esc_url( $icon_url ) .'" target="_blank"><i class="'. esc_attr( $icon_class ) .'"></i></a></span>';
                    }
                }
                echo '</div><!-- .es-social-icons-wrapper -->';
            }
        }

    endif;

/*--------------------- minify css ---------------------------------*/

    if ( ! function_exists( 'easy_store_minify_css' ) ) {

        /**
         * Minify CSS
         *
         * @since 1.0.1
         */
        function easy_store_minify_css( $css = '' ) {

            // Return if no CSS
            if ( ! $css ) return;

            // Normalize whitespace
            $css = preg_replace( '/\s+/', ' ', $css );

            // Remove ; before }
            $css = preg_replace( '/;(?=\s*})/', '', $css );

            // Remove space after , : ; { } */ >
            $css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );

            // Remove space before , ; { }
            $css = preg_replace( '/ (,|;|\{|})/', '$1', $css );

            // Strips leading 0 on decimal values (converts 0.5px into .5px)
            $css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );

            // Strips units if value is 0 (converts 0px to 0)
            $css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );

            // Trim
            $css = trim( $css );

            // Return minified CSS
            return $css;

        }

    }

/*--------------------- Archive title prefix -----------------------*/

    if ( ! function_exists( 'easy_store_archive_title_prefix' ) ) :
            
        /**
         * Archive title prefix
         *
         * @since 1.2.0
         */
        function easy_store_archive_title_prefix( $title ) {

            $title_prefix_enable = easy_store_get_customizer_option_value( 'easy_store_archive_title_prefix_option' );

            if ( false === $title_prefix_enable ) {
                return preg_replace( '/^\w+: /', '', $title );
            } else {
                return $title;
            }
            
        }

    endif;

    add_filter( 'get_the_archive_title', 'easy_store_archive_title_prefix' );

/*--------------------- Convert settings id value ------------------*/

    add_action( 'init', 'easy_store_convert_show_hide_to_boolean' );

    function easy_store_convert_show_hide_to_boolean() {

        $easy_store_new_setup = get_theme_mod( 'easy_store_new_toggle_setup', false );

        if ( true !== $easy_store_new_setup ) {

            $req_settigns_ids = array(
                'easy_store_block_base_widget_editor_option'    => 'hide',
                'easy_store_top_header_option'                  => 'hide',
                'easy_store_header_cart_option'                 => 'show',
                'easy_store_header_wishlist_option'             => 'show',
                'easy_store_primary_menu_sticky'                => 'show',
                'easy_store_store_sidebar_sticky_option'        => 'show',
            );

            foreach( $req_settigns_ids as $key => $value ) {

                $easy_store_setting_value = get_theme_mod( $key, $value );
                if ( ! is_bool( $easy_store_setting_value ) ) {
                    if ( 'hide' == $easy_store_setting_value ) {
                        set_theme_mod( $key, false );
                    } else {
                        set_theme_mod( $key, true );
                    }
                }

            }

            set_theme_mod( 'easy_store_new_toggle_setup', true );

        }
    }