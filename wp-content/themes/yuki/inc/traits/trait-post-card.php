<?php

/**
 * Post card trait
 *
 * @package Yuki
 */
use  LottaFramework\Customizer\Controls\Background ;
use  LottaFramework\Customizer\Controls\Border ;
use  LottaFramework\Customizer\Controls\BoxShadow ;
use  LottaFramework\Customizer\Controls\ImageRadio ;
use  LottaFramework\Customizer\Controls\Placeholder ;
use  LottaFramework\Customizer\Controls\Separator ;
use  LottaFramework\Customizer\Controls\Slider ;
use  LottaFramework\Customizer\Controls\Spacing ;

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly.
}

if ( !trait_exists( 'Yuki_Post_Card' ) ) {
    /**
     * Post card functions
     */
    trait Yuki_Post_Card
    {
        /**
         * @param string $prefix
         * @param array $defaults
         *
         * @return array
         */
        protected function getCardContentControls( $prefix = '', $defaults = array() )
        {
            $defaults = wp_parse_args( $defaults, [
                'spacing'      => '24px',
                'text'         => 'left',
                'vertical'     => 'center',
                'thumb-motion' => 'yes',
            ] );
            return [ ( new Slider( $prefix . 'card_content_spacing' ) )->setLabel( __( 'Content Spacing', 'yuki' ) )->bindSelectiveRefresh( 'yuki-dynamic-css' )->enableResponsive()->setDefaultUnit( 'px' )->setDefaultValue( $defaults['spacing'] ), new Separator(), ( new ImageRadio( $prefix . 'card_content_alignment' ) )->setLabel( __( 'Content Alignment', 'yuki' ) )->bindSelectiveRefresh( 'yuki-dynamic-css' )->enableResponsive()->inlineChoices()->setDefaultValue( $defaults['text'] )->setChoices( [
                'left'   => [
                'src'   => yuki_image( 'text-left' ),
                'title' => __( 'Left', 'yuki' ),
            ],
                'center' => [
                'src'   => yuki_image( 'text-center' ),
                'title' => __( 'Center', 'yuki' ),
            ],
                'right'  => [
                'src'   => yuki_image( 'text-right' ),
                'title' => __( 'Right', 'yuki' ),
            ],
            ] ) ];
        }
        
        /**
         * @return array
         */
        protected function getCardStyleControls( $prefix = '', $defaults = array() )
        {
            $defaults = wp_parse_args( $defaults, [
                'exclude'       => [],
                'background'    => [
                'type'  => 'color',
                'color' => 'var(--yuki-base-color)',
            ],
                'border'        => [ 1, 'solid', 'var(--yuki-base-200)' ],
                'shadow'        => [
                'rgba(44, 62, 80, 0.45)',
                '0px',
                '15px',
                '18px',
                '-15px'
            ],
                'shadow-enable' => true,
                'radius'        => [
                'top'    => '4px',
                'bottom' => '4px',
                'left'   => '4px',
                'right'  => '4px',
                'linked' => true,
            ],
            ] );
            return [
                yuki_upsell_info_control( __( 'Fully customize your posts card style in our %sPro Version%s', 'yuki' ) ),
                ( new Placeholder( $prefix . 'card_background' ) )->setDefaultValue( $defaults['background'] ),
                ( new Placeholder( $prefix . 'card_border' ) )->setDefaultBorder( ...$defaults['border'] ),
                ( new Placeholder( $prefix . 'card_shadow' ) )->setDefaultShadow( ...array_merge( $defaults['shadow'], [ $defaults['shadow-enable'] ] ) ),
                ( new Placeholder( $prefix . 'card_radius' ) )->setDefaultValue( $defaults['radius'] )
            ];
        }
    
    }
}