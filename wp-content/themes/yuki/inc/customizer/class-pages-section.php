<?php
/**
 * Single page customizer section
 *
 * @package Yuki
 */

use LottaFramework\Customizer\Controls\ImageRadio;
use LottaFramework\Customizer\Controls\Section;
use LottaFramework\Customizer\Section as CustomizerSection;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Yuki_Pages_Section' ) ) {

	class Yuki_Pages_Section extends CustomizerSection {

		use Yuki_Article_Controls;

		/**
		 * {@inheritDoc}
		 */
		public function getControls() {
			return [
				( new Section( 'yuki_page_sidebar_section' ) )
					->setLabel( __( 'Sidebar', 'yuki' ) )
					->enableSwitch( false )
					->setControls( [
						( new ImageRadio( 'yuki_page_sidebar_layout' ) )
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

				( new Section( 'yuki_page_header' ) )
					->setLabel( __( 'Page Header', 'yuki' ) )
					->enableSwitch()
					->setControls( $this->getHeaderControls( 'page', [
						'metas' => [
							'elements' => [
								[ 'id' => 'published', 'visible' => true ]
							],
						],
					] ) )
				,

				( new Section( 'yuki_page_featured_image' ) )
					->setLabel( __( 'Featured Image', 'yuki' ) )
					->enableSwitch()
					->setControls( $this->getFeaturedImageControls( 'page', [
						'image-style' => 'below'
					] ) )
				,
			];
		}
	}
}


