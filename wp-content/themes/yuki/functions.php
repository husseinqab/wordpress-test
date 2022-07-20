<?php

/**
 * Yuki functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Yuki
 */
if ( !defined( 'YUKI_VERSION' ) ) {
    // Replace the version number of the theme on each release.
    define( 'YUKI_VERSION', '1.1.0' );
}
if ( !defined( 'YUKI_WOOCOMMERCE_ACTIVE' ) ) {
    // Used to check whether WooCommerce plugin is activated
    define( 'YUKI_WOOCOMMERCE_ACTIVE', class_exists( 'WooCommerce' ) );
}

if ( !function_exists( 'yuki_fs' ) ) {
    // Create a helper function for easy SDK access.
    function yuki_fs()
    {
        global  $yuki_fs ;
        
        if ( !isset( $yuki_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $yuki_fs = fs_dynamic_init( array(
                'id'             => '10671',
                'slug'           => 'yuki',
                'type'           => 'theme',
                'public_key'     => 'pk_add32a34a0ba63b92abede52e5046',
                'is_premium'     => false,
                'premium_suffix' => 'Professional',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
                'slug'   => 'yuki',
                'parent' => array(
                'slug' => 'themes.php',
            ),
            ),
                'is_live'        => true,
            ) );
        }
        
        return $yuki_fs;
    }
    
    // Init Freemius.
    yuki_fs();
    // Signal that SDK was initiated.
    do_action( 'yuki_fs_loaded' );
}

/**
 * Load lotta-framework
 */
require get_template_directory() . '/lotta-framework/vendor/autoload.php';
/**
 * Helper functions
 */
require get_template_directory() . '/inc/helpers.php';
/**
 * Dynamic Css
 */
require get_template_directory() . '/inc/dynamic-css.php';
/**
 * Theme Setup
 */
require get_template_directory() . '/inc/theme-setup.php';
if ( YUKI_WOOCOMMERCE_ACTIVE ) {
    /**
     * WooCommerce Setup
     */
    require get_template_directory() . '/inc/woo-setup.php';
}
/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';
/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';
/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';
/**
 * Boostrap lotta-framework
 */
\LottaFramework\Bootstrap::run( 'yuki', trailingslashit( get_template_directory_uri() ) . 'lotta-framework/' );
// support locally hosted google-fonts
yuki_app()->support( 'local_webfonts' );
// save theme settings in options
yuki_app( 'CZ' )->storeAs( 'option' );
// add global customize partial
yuki_app( 'CZ' )->addPartial( 'yuki-dynamic-css', '#yuki-selective-dynamic-css', function () {
    $web_font_url = \LottaFramework\Typography\Fonts::get_webfont_url( 'yuki_fonts' );
    if ( $web_font_url !== '' ) {
        echo  '<link rel="stylesheet" href="' . $web_font_url . '" media="all">' ;
    }
    echo  '<style>' ;
    Yuki_Header_Builder::instance()->builder()->do( 'enqueue_frontend_scripts' );
    Yuki_Footer_Builder::instance()->builder()->do( 'enqueue_frontend_scripts' );
    if ( is_front_page() && !is_home() && yuki_app( 'CZ' )->checked( 'yuki_homepage_builder_section' ) ) {
        Yuki_Homepage_Builder::enqueue_frontend_scripts();
    }
    echo  yuki_global_css_vars() ;
    echo  yuki_dynamic_css() ;
    echo  '</style>' ;
} );
/**
 * After lotta-framework boostrap
 */
do_action( 'yuki_after_lotta_framework_bootstrap' );