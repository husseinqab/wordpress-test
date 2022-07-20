<?php
/**
 * Header builder row
 *
 * @package Yuki
 */

use LottaFramework\Customizer\Controls\Background;
use LottaFramework\Customizer\Controls\Border;
use LottaFramework\Customizer\Controls\BoxShadow;
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

if ( ! class_exists( 'Yuki_Header_Row' ) ) {

	class Yuki_Header_Row extends Row {

		/**
		 * {@inheritDoc}
		 */
		public function enqueue_frontend_scripts() {

			// Add dynamic css for row
			add_filter( 'yuki_filter_dynamic_css', function ( $css ) {

				$css[".yuki-header-row-{$this->id}"] = array_merge(
					Css::background( CZ::get( $this->getRowControlKey( 'background' ) ) ),
					Css::shadow( CZ::get( $this->getRowControlKey( 'shadow' ) ) ),
					Css::border( CZ::get( $this->getRowControlKey( 'border_top' ) ), 'border-top' ),
					Css::border( CZ::get( $this->getRowControlKey( 'border_bottom' ) ), 'border-bottom' ),
					[
						'display' => CZ::display( $this->getRowControlKey( 'visible' ) ),
					]
				);

				$css[".yuki-header-row-{$this->id} .container"] = [
					'min-height' => CZ::get( $this->getRowControlKey( 'min_height' ) )
				];

				return $css;
			} );
		}

		/**
		 * {@inheritDoc}
		 */
		public function beforeRow() {

			$attrs = [
				'class'    => 'yuki-header-row yuki-header-row-' . $this->id,
				'data-row' => $this->id,
			];

			if ( is_customize_preview() ) {
				$attrs['data-shortcut']          = 'border';
				$attrs['data-shortcut-location'] = 'yuki_header:' . $this->id;
			}

			echo '<div ' . Utils::render_attribute_string( $attrs ) . '>';
			echo '<div class="container mx-auto text-xs px-gutter flex flex-wrap items-stretch">';
		}

		/**
		 * {@inheritDoc}
		 */
		public function afterRow() {
			echo '</div></div>';
		}

		/**
		 * @param $key
		 *
		 * @return string
		 */
		protected function getRowControlKey( $key ) {
			return 'yuki_header_' . $this->id . '_row_' . $key;
		}

		/**
		 * {@inheritDoc}
		 *
		 * @return array
		 */
		protected function getRowControls() {
			return [
				( new Tabs() )
					->setActiveTab( 'content' )
					->addTab( 'content', __( 'Content', 'yuki' ), [
						( new Slider( $this->getRowControlKey( 'min_height' ) ) )
							->setLabel( __( 'Min Height', 'yuki' ) )
							->setDefaultValue( $this->getRowControlDefault( 'min_height', '80px' ) )
							->setDefaultUnit( 'px' )
							->enableResponsive()
							->setMin( 20 )
							->setMax( 300 )
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
						( new Border( $this->getRowControlKey( 'border_top' ) ) )
							->setLabel( __( 'Top Border', 'yuki' ) )
							->enableResponsive()
							->displayBlock()
							->setDefaultBorder(
								...$this->getRowControlDefault( 'border_top', [ 1, 'none', 'var(--yuki-base-200)' ] )
							)
						,
						( new Separator() )->setStyle( 'dashed' ),
						( new Border( $this->getRowControlKey( 'border_bottom' ) ) )
							->setLabel( __( 'Bottom Border', 'yuki' ) )
							->enableResponsive()
							->displayBlock()
							->setDefaultBorder(
								...$this->getRowControlDefault( 'border_bottom', [
								1,
								'none',
								'var(--yuki-base-200)'
							] )
							)
						,
						( new Separator() )->setStyle( 'dashed' ),
						( new BoxShadow( $this->getRowControlKey( 'shadow' ) ) )
							->setLabel( __( 'Box Shadow', 'yuki' ) )
							->enableResponsive()
							->displayBlock()
							->setDefaultShadow(
								...$this->getRowControlDefault( 'shadow', [
									'rgba(44, 62, 80, 0.05)',
									'0px',
									'10px',
									'10px',
									'0px',
									false
								]
							) )
						,
						( new Separator() )->setStyle( 'dashed' ),
						( new Background( $this->getRowControlKey( 'background' ) ) )
							->setLabel( __( 'Background', 'yuki' ) )
							->enableResponsive()
							->setDefaultValue( $this->getRowControlDefault( 'background', [
								'type'  => 'color',
								'color' => 'var(--yuki-base-color)'
							] ) )
					] )
			];
		}
	}
}