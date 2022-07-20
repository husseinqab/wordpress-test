<?php

/**
 * Header builder instance
 *
 * @package Yuki
 */
use  LottaFramework\Customizer\Controls\Builder ;

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly.
}

if ( !class_exists( 'Yuki_Header_Builder' ) ) {
    class Yuki_Header_Builder
    {
        /**
         * @var null
         */
        protected static  $_instance = null ;
        /**
         * @var Builder|null
         */
        protected  $_builder = null ;
        /**
         * Construct builder
         */
        protected function __construct()
        {
            $this->_builder = ( new Builder( 'yuki_header_builder' ) )->setLabel( __( 'Header Elements', 'yuki' ) )->showLabel()->bindSelectiveRefresh( 'yuki-dynamic-css' )->selectiveRefresh( '.yuki-site-header', 'yuki_header_render' )->enableResponsive()->setColumn( Yuki_Header_Column::instance() );
            // Add elements
            $this->_builder->addElement( new Yuki_Logo_Element( 'logo', 'yuki_header_el_logo', __( 'Logo', 'yuki' ) ) )->addElement( new Yuki_Socials_Element( 'socials', 'yuki_header_el_socials', __( 'Socials', 'yuki' ) ) )->addElement( new Yuki_Search_Element( 'search', 'yuki_header_el_search', __( 'Search', 'yuki' ) ) )->addElement( new Yuki_Divider_Element( 'divider-1', 'yuki_header_el_divider_1', __( 'Divider #1', 'yuki' ) ) )->addElement( new Yuki_Divider_Element( 'divider-2', 'yuki_header_el_divider_2', __( 'Divider #2', 'yuki' ) ) )->addElement( new Yuki_Trigger_Element( 'trigger', 'yuki_header_el_trigger', __( 'Trigger', 'yuki' ) ) )->addElement( new Yuki_Theme_Switch_Element( 'theme-switch', 'yuki_header_el_theme_switch', __( 'Theme Switch', 'yuki' ) ) )->addElement( new Yuki_Widgets_Element( 'widgets', 'yuki_header_el_widgets', __( 'Off Canvas Widgets', 'yuki' ) ) )->addElement( new Yuki_Collapsable_Menu_Element( 'collapsable-menu', 'yuki_header_el_collapsable-menu', __( 'Collapsable Menu', 'yuki' ) ) )->addElement( ( new Yuki_Menu_Element(
                'menu-1',
                'yuki_header_el_menu_1',
                __( 'Menu #1', 'yuki' ),
                [
                'depth'             => 1,
                'top-level-padding' => [
                'top'    => '6px',
                'bottom' => '6px',
                'left'   => '8px',
                'right'  => '8px',
            ],
            ]
            ) )->desktopOnly() )->addElement( ( new Yuki_Menu_Element( 'menu-2', 'yuki_header_el_menu_2', __( 'Menu #2', 'yuki' ) ) )->desktopOnly() )->addElement( new Yuki_Button_Element( 'button-1', 'yuki_header_el_button_1', __( 'Button #1', 'yuki' ) ) );
            // add rows
            $this->_builder->addRow( ( new Yuki_Modal_Row( 'modal', __( 'Modal Area', 'yuki' ) ) )->isOffCanvas()->addDesktopColumn( [ 'widgets' ], [
                'direction' => 'column',
            ] )->addMobileColumn( [ 'collapsable-menu' ], [
                'direction' => 'column',
            ] ) )->addRow( ( new Yuki_Header_Row( 'top_bar', __( 'Top Bar', 'yuki' ), [
                'min_height'    => '90px',
                'border_bottom' => [ 1, 'solid', 'var(--yuki-base-200)' ],
                'background'    => [
                'type'  => 'color',
                'color' => 'var(--yuki-base-color)',
            ],
            ] ) )->setMaxColumns( 3 )->addDesktopColumn( [ 'logo' ], [
                'width' => '30%',
            ] )->addDesktopColumn( [ 'menu-1', 'divider-1', 'button-1' ], [
                'width'           => '70%',
                'justify-content' => 'flex-end',
            ] )->addMobileColumn( [ 'logo' ], [
                'width' => '50%',
            ] )->addMobileColumn( [ 'button-1' ], [
                'width'           => '50%',
                'justify-content' => 'flex-end',
            ] ) )->addRow( ( new Yuki_Header_Row( 'primary_navbar', __( 'Primary Navbar', 'yuki' ), [
                'min_height'    => '50px',
                'border_bottom' => [ 1, 'solid', 'var(--yuki-base-200)' ],
            ] ) )->setMaxColumns( 3 )->addDesktopColumn( [ 'menu-2' ], [
                'width' => '70%',
            ] )->addDesktopColumn( [
                'socials',
                'theme-switch',
                'search',
                'trigger'
            ], [
                'width'           => '30%',
                'justify-content' => 'flex-end',
            ] )->addMobileColumn( [ 'search' ], [
                'width' => '30%',
            ] )->addMobileColumn( [ 'socials' ], [
                'width'           => '40%',
                'justify-content' => 'center',
            ] )->addMobileColumn( [ 'theme-switch', 'trigger' ], [
                'width'           => '30%',
                'justify-content' => 'flex-end',
            ] ) )->addRow( ( new Yuki_Header_Row( 'bottom_row', __( 'Bottom Row', 'yuki' ) ) )->setMaxColumns( 3 )->addDesktopColumn( [], [
                'width' => '100%',
            ] )->addMobileColumn( [], [
                'width' => '100%',
            ] ) );
        }
        
        /**
         * Get header builder
         *
         * @return Yuki_Header_Builder|null
         */
        public static function instance()
        {
            if ( self::$_instance === null ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        
        /**
         * Magic static calls
         *
         * @param $method
         * @param $args
         *
         * @return mixed
         */
        public static function __callStatic( $method, $args )
        {
            $builder = self::instance()->builder();
            if ( method_exists( $builder, $method ) ) {
                return $builder->{$method}( ...$args );
            }
            return null;
        }
        
        /**
         * @return Builder|null
         */
        public function builder()
        {
            return $this->_builder;
        }
    
    }
}