<?php
/**
 * Single post customizer section
 *
 * @package Yuki
 */

use LottaFramework\Customizer\Controls\Border;
use LottaFramework\Customizer\Controls\ColorPicker;
use LottaFramework\Customizer\Controls\Icons;
use LottaFramework\Customizer\Controls\ImageRadio;
use LottaFramework\Customizer\Controls\Section;
use LottaFramework\Customizer\Controls\Separator;
use LottaFramework\Customizer\Controls\Spacing;
use LottaFramework\Customizer\Section as CustomizerSection;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Yuki_Single_Post_Section' ) ) {

	class Yuki_Single_Post_Section extends CustomizerSection {

		use Yuki_Article_Controls;

		/**
		 * {@inheritDoc}
		 */
		public function getControls() {
			return [
				( new Section( 'yuki_post_sidebar_section' ) )
					->setLabel( __( 'Sidebar', 'yuki' ) )
					->enableSwitch( false )
					->setControls( [
						( new ImageRadio( 'yuki_post_sidebar_layout' ) )
							->setLabel( __( 'Sidebar Layout', 'yuki' ) )
							->setDefaultValue( 'right-sidebar' )
							->setChoices( [
								'left-sidebar'  => [
									'title' => __( 'Left Sidebar', 'yuki' ),
									'src'   => yuki_image_url( 'left-sidebar.png' ),
								],
								'right-sidebar' => [
									'title' => __( 'Right Sidebar', 'yuki' ),
									'src'   => yuki_image_url( 'right-sidebar.png' ),
								],
							] )
						,
					] )
				,

				( new Section( 'yuki_post_header' ) )
					->setLabel( __( 'Post Header', 'yuki' ) )
					->enableSwitch()
					->setControls( $this->getHeaderControls( 'post' ) )
				,

				( new Section( 'yuki_post_featured_image' ) )
					->setLabel( __( 'Featured Image', 'yuki' ) )
					->enableSwitch()
					->setControls( $this->getFeaturedImageControls( 'post' ) )
				,

				( new Section( 'yuki_post_navigation' ) )
					->setLabel( __( 'Posts Navigation', 'yuki' ) )
					->enableSwitch()
					->setControls( $this->getNavigationControls( 'post' ) )
				,
			];
		}

		/**
		 * @return array
		 */
		protected function getNavigationControls( $type ) {
			return [
				( new ColorPicker( 'yuki_' . $type . '_navigation_text_color' ) )
					->setLabel( __( 'Text Color', 'yuki' ) )
					->bindSelectiveRefresh( 'yuki-dynamic-css' )
					->addColor( 'initial', __( 'Initial', 'yuki' ), 'var(--yuki-accent-color)' )
					->addColor( 'hover', __( 'Hover', 'yuki' ), 'var(--yuki-primary-color)' )
				,
				( new Separator() ),
				( new Icons( 'yuki_' . $type . '_navigation_prev_icon' ) )
					->setLabel( __( 'Prev Icon', 'yuki' ) )
					->setDefaultValue( [
						'value'   => 'fas fa-arrow-left-long',
						'library' => 'fa-solid',
					] )
				,
				( new Icons( 'yuki_' . $type . '_navigation_next_icon' ) )
					->setLabel( __( 'Prev Icon', 'yuki' ) )
					->setDefaultValue( [
						'value'   => 'fas fa-arrow-right-long',
						'library' => 'fa-solid',
					] )
				,
				( new Separator() ),
				( new Border( 'yuki_' . $type . '_navigation_border_top' ) )
					->setLabel( __( 'Border Top', 'yuki' ) )
					->bindSelectiveRefresh( 'yuki-dynamic-css' )
					->setDefaultBorder( 1, 'dashed', 'var(--yuki-base-200)' )
				,
				( new Border( 'yuki_' . $type . '_navigation_border_bottom' ) )
					->setLabel( __( 'Border Bottom', 'yuki' ) )
					->bindSelectiveRefresh( 'yuki-dynamic-css' )
					->setDefaultBorder( 1, 'dashed', 'var(--yuki-base-200)' )
				,
				( new Separator() ),
				( new Spacing( 'yuki_' . $type . '_navigation_padding' ) )
					->setLabel( __( 'Padding', 'yuki' ) )
					->bindSelectiveRefresh( 'yuki-dynamic-css' )
					->setDisabled( [ 'left', 'right' ] )
					->setSpacing( [
						'top'    => '24px',
						'bottom' => '24px',
						'linked' => true
					] )
				,
				( new Spacing( 'yuki_' . $type . '_navigation_margin' ) )
					->setLabel( __( 'Padding', 'yuki' ) )
					->bindSelectiveRefresh( 'yuki-dynamic-css' )
					->setDisabled( [ 'left', 'right' ] )
					->setSpacing( [
						'top'    => '24px',
						'bottom' => '24px',
						'linked' => true
					] )
				,
			];
		}
	}
}

