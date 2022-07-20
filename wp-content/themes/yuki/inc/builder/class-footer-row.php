<?php
/**
 * Footer builder row
 *
 * @package Yuki
 */

use LottaFramework\Customizer\Controls\Background;
use LottaFramework\Customizer\Controls\Border;
use LottaFramework\Customizer\Controls\Separator;
use LottaFramework\Customizer\Controls\Slider;
use LottaFramework\Customizer\Controls\Tabs;
use LottaFramework\Customizer\Controls\Toggle;
use LottaFramework\Customizer\GenericBuilder\Row;
use LottaFramework\Facades\Css;
use LottaFramework\Facades\CZ;
use LottaFramework\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Yuki_Footer_Row' ) ) {

	class Yuki_Footer_Row extends Row {

		/**
		 * {@inheritDoc}
		 */
		public function enqueue_frontend_scripts() {
			// Add dynamic css for row
			add_filter( 'yuki_filter_dynamic_css', function ( $css ) {

				$css[".yuki-footer-row-{$this->id}"] = array_merge(
					[
						'display'        => CZ::display( $this->getRowControlKey( 'visible' ) ),
						'padding-top'    => CZ::get( $this->getRowControlKey( 'vt_spacing' ) ),
						'padding-bottom' => CZ::get( $this->getRowControlKey( 'vt_spacing' ) ),
					],
					Css::background( CZ::get( $this->getRowControlKey( 'background' ) ) ),
					Css::border( CZ::get( $this->getRowControlKey( 'border_top' ) ), 'border-top' ),
					Css::border( CZ::get( $this->getRowControlKey( 'border_bottom' ) ), 'border-bottom' )
				);

				return $css;
			} );
		}

		/**
		 * {@inheritDoc}
		 */
		public function beforeRowDevice( $device, $settings ) {
			$attrs = [
				'class'    => 'yuki-footer-row yuki-footer-row-' . $this->id,
				'data-row' => $this->id,
			];

			if ( is_customize_preview() ) {
				$attrs['data-shortcut']          = 'border';
				$attrs['data-shortcut-location'] = 'yuki_footer:' . $this->id;
			}

			echo '<div ' . Utils::render_attribute_string( $attrs ) . '>';
			echo '<div class="container mx-auto px-gutter flex flex-wrap">';
		}

		/**
		 * {@inheritDoc}
		 */
		public function afterRowDevice( $device, $settings ) {
			echo '</div></div>';
		}

		/**
		 * @param $key
		 *
		 * @return string
		 */
		protected function getRowControlKey( $key ) {
			return 'yuki_footer_' . $this->id . '_row_' . $key;
		}

		/**
		 * {@inheritDoc}
		 */
		protected function getRowControls() {
			return [
				( new Tabs() )
					->setActiveTab( 'general' )
					->addTab( 'general', __( 'General', 'yuki' ), [
						( new Slider( $this->getRowControlKey( 'vt_spacing' ) ) )
							->setLabel( __( 'Vertical Spacing', 'yuki' ) )
							->enableResponsive()
							->setMin( 0 )
							->setMax( 100 )
							->setDefaultUnit( 'px' )
							->setDefaultValue( '24px' )
						,
						( new Separator() ),
						( new Toggle( $this->getRowControlKey( 'visible' ) ) )
							->setLabel( __( 'Visible', 'yuki' ) )
							->enableResponsive()
							->displayBlock()
							->openByDefault()
						,
					] )
					->addTab( 'style', __( 'Style', 'yuki' ), [
						( new Background( $this->getRowControlKey( 'background' ) ) )
							->setLabel( __( 'Background', 'yuki' ) )
							->enableResponsive()
							->setDefaultValue( [
								'type'  => 'color',
								'color' => 'var(--yuki-base-color)'
							] )
						,
						( new Separator() ),
						( new Border( $this->getRowControlKey( 'border_top' ) ) )
							->setLabel( __( 'Top Border', 'yuki' ) )
							->enableResponsive()
							->displayBlock()
							->setDefaultBorder(
								...$this->getRowControlDefault( 'border_top', [ 1, 'none', 'var(--yuki-base-300)' ] )
							)
						,
						( new Separator() ),
						( new Border( $this->getRowControlKey( 'border_bottom' ) ) )
							->setLabel( __( 'Bottom Border', 'yuki' ) )
							->enableResponsive()
							->displayBlock()
							->setDefaultBorder(
								...$this->getRowControlDefault( 'border_bottom', [
								1,
								'none',
								'var(--yuki-base-300)'
							] )
							)
						,
					] )
			];
		}
	}
}
