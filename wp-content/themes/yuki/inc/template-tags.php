<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Yuki
 */

use LottaFramework\Facades\Css;
use LottaFramework\Facades\CZ;
use LottaFramework\Icons\IconsManager;
use LottaFramework\Utils;

if ( ! function_exists( 'yuki_html_attributes' ) ) {
	/**
	 * Output html attributes
	 */
	function yuki_html_attributes() {

		$attrs = [
			'data-yuki-theme' => $_COOKIE['yuki-color-mode'] ?? CZ::get( 'yuki_default_color_scheme' ),
		];

		Utils::print_attribute_string( apply_filters( 'yuki_html_attributes', $attrs ) );
	}
}

if ( ! function_exists( 'yuki_image_size_options' ) ) {
	/**
	 * @param bool $add_disable
	 * @param array $allowed
	 * @param bool $show_dimension
	 *
	 * @return array
	 */
	function yuki_image_size_options( $add_disable = true, array $allowed = [], $show_dimension = true ) {

		global $_wp_additional_image_sizes;

		$choices = [];

		if ( true === $add_disable ) {
			$choices['disable'] = 'No Image';
		}

		$choices['thumbnail'] = 'Thumbnail';
		$choices['medium']    = 'Medium';
		$choices['large']     = 'Large';
		$choices['full']      = 'Full (original)';

		if ( true === $show_dimension ) {
			foreach ( [ 'thumbnail', 'medium', 'large' ] as $_size ) {
				$choices[ $_size ] = $choices[ $_size ] . ' (' . get_option( $_size . '_size_w' ) . 'x' . get_option( $_size . '_size_h' ) . ')';
			}
		}

		if ( ! empty( $_wp_additional_image_sizes ) && is_array( $_wp_additional_image_sizes ) ) {
			foreach ( $_wp_additional_image_sizes as $key => $size ) {
				$choices[ $key ] = $key;
				if ( true === $show_dimension ) {
					$choices[ $key ] .= ' (' . $size['width'] . 'x' . $size['height'] . ')';
				}
			}
		}

		if ( ! empty( $allowed ) ) {
			foreach ( $choices as $key => $value ) {
				if ( ! in_array( $key, $allowed, true ) ) {
					unset( $choices[ $key ] );
				}
			}
		}

		return $choices;
	}
}

if ( ! function_exists( 'yuki_image' ) ) {
	/**
	 * Get image file
	 *
	 * @param $name
	 *
	 * @return mixed|string
	 */
	function yuki_image( $name ) {
		$svgs = [
			'none'                    => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32"><path d="M9.943 17.415h-0.065l-2.095-3.199-2.559-3.777h-1.984v11.025h2.191v-6.944h0.095l1.819 2.784 2.793 4.16h1.996v-11.025h-2.191v6.977zM12.904 22.135h1.615l4.049-12.271h-1.633l-4.031 12.271zM24.92 10.439h-2.24l-3.874 11.025h2.336l0.72-2.273h3.841l0.672 2.273h2.384l-3.84-11.025zM22.455 17.352l0.447-1.456 0.85-2.864h0.063l0.866 2.913 0.431 1.408h-2.656z"></path></svg>',
			/**
			 * Divider
			 */
			'divider-1'               => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path d="M9,17.2l5.1-10.9L15,6.8L9.9,17.6L9,17.2z"/></svg>',
			'divider-2'               => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path d="M8.9,11.5h6.3v1H8.9V11.5z"/></svg>',
			'divider-3'               => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 20 20"><path d="M7.8 10c0 1.215 0.986 2.2 2.201 2.2s2.199-0.986 2.199-2.2c0-1.215-0.984-2.199-2.199-2.199s-2.201 0.984-2.201 2.199z"></path></svg>',
			/**
			 * Text Align
			 */
			'text-left'               => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 32 32"><path d="M3.328 14.4c-1.056 0-1.984-0.8-1.984-1.728s0.928-1.728 1.984-1.728h24c1.088 0 2.016 0.8 2.016 1.728s-0.928 1.728-2.016 1.728h-24zM3.328 21.056c-1.056 0-1.984-0.8-1.984-1.728s0.928-1.728 1.984-1.728h20c1.088 0 2.016 0.8 2.016 1.728s-0.928 1.728-2.016 1.728h-20zM3.328 27.744c-1.056 0-1.984-0.8-1.984-1.76s0.928-1.728 1.984-1.728h25.344c1.056 0 1.984 0.8 1.984 1.728s-0.928 1.76-1.984 1.76h-25.344zM3.328 7.744c-1.056 0-1.984-0.8-1.984-1.76s0.928-1.728 1.984-1.728h17.344c1.056 0 1.984 0.8 1.984 1.728s-0.928 1.76-1.984 1.76h-17.344z"></path></svg>',
			'text-center'             => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 32 32"><path d="M6.016 14.4c-1.088 0-2.016-0.8-2.016-1.728s0.928-1.728 2.016-1.728h19.84c1.216 0 2.016 0.8 2.016 1.728s-0.928 1.728-2.016 1.728h-19.84zM8.672 21.056c-1.056 0-2.016-0.8-2.016-1.728s0.96-1.728 2.016-1.728h14.656c1.088 0 2.016 0.8 2.016 1.728s-0.928 1.728-2.016 1.728h-14.656zM3.328 27.744c-1.056 0-1.984-0.8-1.984-1.76s0.928-1.728 1.984-1.728h25.344c1.056 0 1.984 0.8 1.984 1.728s-0.928 1.76-1.984 1.76h-25.344zM11.456 7.744c-1.184 0-2.112-0.8-2.112-1.76s0.928-1.728 2.112-1.728h9.088c1.184 0 2.112 0.8 2.112 1.728s-0.928 1.76-2.112 1.76h-9.088z"></path></svg>',
			'text-right'              => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 32 32"><path d="M28.672 14.4h-24c-1.056 0-2.016-0.8-2.016-1.728s0.96-1.728 2.016-1.728h24c1.056 0 1.984 0.8 1.984 1.728s-0.928 1.728-1.984 1.728zM28.672 21.056h-20c-1.056 0-2.016-0.8-2.016-1.728s0.96-1.728 2.016-1.728h20c1.056 0 1.984 0.8 1.984 1.728s-0.928 1.728-1.984 1.728zM28.672 27.744h-25.344c-1.056 0-1.984-0.8-1.984-1.76s0.928-1.728 1.984-1.728h25.344c1.056 0 1.984 0.8 1.984 1.728s-0.928 1.76-1.984 1.76zM28.672 7.744h-17.344c-1.056 0-1.984-0.8-1.984-1.76s0.928-1.728 1.984-1.728h17.344c1.056 0 1.984 0.8 1.984 1.728s-0.928 1.76-1.984 1.76z"></path></svg>',
			'text-justify'            => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 32 32"><path d="M3.328 27.744c-1.056 0-1.984-0.8-1.984-1.76s0.928-1.728 1.984-1.728h25.344c1.056 0 1.984 0.8 1.984 1.728s-0.928 1.76-1.984 1.76h-25.344zM3.328 21.056c-1.056 0-1.984-0.8-1.984-1.728s0.928-1.728 1.984-1.728h25.344c1.056 0 1.984 0.8 1.984 1.728s-0.928 1.728-1.984 1.728h-25.344zM3.328 14.4c-1.056 0-1.984-0.8-1.984-1.728s0.928-1.728 1.984-1.728h25.344c1.056 0 1.984 0.8 1.984 1.728s-0.928 1.728-1.984 1.728h-25.344zM3.328 7.744c-1.056 0-1.984-0.8-1.984-1.76s0.928-1.728 1.984-1.728h25.344c1.056 0 1.984 0.8 1.984 1.728s-0.928 1.76-1.984 1.76h-25.344z"></path></svg>',
			/**
			 * Justify Content
			 */
			'justify-space-between-v' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 32 32"><path d="M30.656 29.344c0.736 0 1.344 0.576 1.344 1.312 0 0.704-0.512 1.28-1.184 1.344h-29.472c-0.736 0-1.344-0.608-1.344-1.344 0-0.672 0.512-1.248 1.184-1.312h29.472zM24.8 21.344c1.024 0 1.856 0.832 1.856 1.856v3.456h-21.312v-3.456c0-1.024 0.832-1.856 1.856-1.856h17.6zM26.656 5.344v3.456c0 1.024-0.832 1.856-1.856 1.856h-17.6c-1.024 0-1.856-0.832-1.856-1.856v-3.456h21.312zM30.656 0c0.736 0 1.344 0.608 1.344 1.344 0 0.672-0.512 1.248-1.184 1.312h-29.472c-0.736 0-1.344-0.576-1.344-1.312 0-0.704 0.512-1.28 1.184-1.344h29.472z"></path></svg>',
			'justify-space-around-v'  => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 32 32"><path d="M30.656 29.344c0.736 0 1.344 0.576 1.344 1.312 0 0.704-0.512 1.28-1.184 1.344h-29.472c-0.736 0-1.344-0.608-1.344-1.344 0-0.672 0.512-1.248 1.184-1.312h29.472zM24.8 17.344c1.024 0 1.856 0.832 1.856 1.856v1.6c0 1.024-0.832 1.856-1.856 1.856h-17.6c-1.024 0-1.856-0.832-1.856-1.856v-1.6c0-1.024 0.832-1.856 1.856-1.856h17.6zM24.8 9.344c1.024 0 1.856 0.832 1.856 1.856v1.6c0 1.024-0.832 1.856-1.856 1.856h-17.6c-1.024 0-1.856-0.832-1.856-1.856v-1.6c0-1.024 0.832-1.856 1.856-1.856h17.6zM30.656 0c0.736 0 1.344 0.608 1.344 1.344 0 0.672-0.512 1.248-1.184 1.312h-29.472c-0.736 0-1.344-0.576-1.344-1.312 0-0.704 0.512-1.28 1.184-1.344h29.472z"></path></svg>',
			'justify-start-v'         => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 32 32"><path d="M24.928 20c0.96 0 1.728 0.768 1.728 1.728v1.856c0 0.96-0.768 1.76-1.728 1.76h-17.856c-0.96 0-1.728-0.8-1.728-1.76v-1.856c0-0.96 0.768-1.728 1.728-1.728h17.856zM24.928 12c0.96 0 1.728 0.768 1.728 1.728v1.856c0 0.96-0.768 1.76-1.728 1.76h-17.856c-0.96 0-1.728-0.8-1.728-1.76v-1.856c0-0.96 0.768-1.728 1.728-1.728h17.856zM30.656 6.656c0.736 0 1.344 0.608 1.344 1.344 0 0.672-0.512 1.248-1.184 1.312l-0.16 0.032h-29.312c-0.736 0-1.344-0.608-1.344-1.344 0-0.672 0.512-1.248 1.184-1.312l0.16-0.032h29.312z"></path></svg>',
			'justify-center-v'        => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 32 32"><path d="M24.928 20c0.96 0 1.728 0.768 1.728 1.728v1.856c0 0.96-0.768 1.76-1.728 1.76h-17.856c-0.96 0-1.728-0.8-1.728-1.76v-1.856c0-0.96 0.768-1.728 1.728-1.728h17.856zM30.656 14.656c0.736 0 1.344 0.608 1.344 1.344 0 0.672-0.512 1.248-1.184 1.312l-0.16 0.032h-29.312c-0.736 0-1.344-0.608-1.344-1.344 0-0.672 0.512-1.248 1.184-1.312l0.16-0.032h29.312zM24.928 6.656c0.96 0 1.728 0.768 1.728 1.728v1.888c0 0.96-0.768 1.728-1.728 1.728h-17.856c-0.96 0-1.728-0.768-1.728-1.728v-1.888c0-0.96 0.768-1.728 1.728-1.728h17.856z"></path></svg>',
			'justify-end-v'           => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 32 32"><path d="M30.656 22.656c0.736 0 1.344 0.608 1.344 1.344 0 0.672-0.512 1.248-1.184 1.312l-0.16 0.032h-29.312c-0.736 0-1.344-0.608-1.344-1.344 0-0.672 0.512-1.248 1.184-1.312l0.16-0.032h29.312zM24.928 14.656c0.96 0 1.728 0.768 1.728 1.728v1.888c0 0.96-0.768 1.728-1.728 1.728h-17.856c-0.96 0-1.728-0.768-1.728-1.728v-1.888c0-0.96 0.768-1.728 1.728-1.728h17.856zM24.928 6.656c0.96 0 1.728 0.768 1.728 1.728v1.888c0 0.96-0.768 1.728-1.728 1.728h-17.856c-0.96 0-1.728-0.768-1.728-1.728v-1.888c0-0.96 0.768-1.728 1.728-1.728h17.856z"></path></svg>',

			'justify-space-between-h' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 32 32"><path d="M1.344 0c0.672 0 1.248 0.512 1.312 1.184v29.472c0 0.736-0.576 1.344-1.312 1.344-0.704 0-1.248-0.512-1.344-1.184v-29.472c0-0.736 0.608-1.344 1.344-1.344zM30.656 0c0.704 0 1.28 0.512 1.344 1.184v29.472c0 0.736-0.608 1.344-1.344 1.344-0.672 0-1.248-0.512-1.312-1.184v-29.472c0-0.736 0.576-1.344 1.312-1.344zM8.8 5.344c1.024 0 1.856 0.832 1.856 1.856v17.6c0 1.024-0.832 1.856-1.856 1.856h-3.456v-21.312h3.456zM26.656 5.344v21.312h-3.456c-1.024 0-1.856-0.832-1.856-1.856v-17.6c0-1.024 0.832-1.856 1.856-1.856h3.456z"></path></svg>',
			'justify-space-around-h'  => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 32 32"><path d="M30.656 0c0.704 0 1.28 0.512 1.344 1.184v29.472c0 0.736-0.608 1.344-1.344 1.344-0.672 0-1.248-0.512-1.312-1.184v-29.472c0-0.736 0.576-1.344 1.312-1.344zM1.344 0c0.672 0 1.248 0.512 1.312 1.184v29.472c0 0.736-0.576 1.344-1.312 1.344-0.704 0-1.248-0.512-1.344-1.184v-29.472c0-0.736 0.608-1.344 1.344-1.344zM20.8 5.344c1.024 0 1.856 0.832 1.856 1.856v17.6c0 1.024-0.832 1.856-1.856 1.856h-1.6c-1.024 0-1.856-0.832-1.856-1.856v-17.6c0-1.024 0.832-1.856 1.856-1.856h1.6zM12.8 5.344c1.024 0 1.856 0.832 1.856 1.856v17.6c0 1.024-0.832 1.856-1.856 1.856h-1.6c-1.024 0-1.856-0.832-1.856-1.856v-17.6c0-1.024 0.832-1.856 1.856-1.856h1.6z"></path></svg>',
			'justify-start-h'         => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 32 32"><path d="M8 0c0.672 0 1.248 0.512 1.312 1.184l0.032 0.16v29.312c0 0.736-0.608 1.344-1.344 1.344-0.672 0-1.248-0.512-1.312-1.184l-0.032-0.16v-29.312c0-0.736 0.608-1.344 1.344-1.344zM15.616 5.344c0.96 0 1.728 0.768 1.728 1.728v17.856c0 0.96-0.768 1.728-1.728 1.728h-1.888c-0.96 0-1.728-0.768-1.728-1.728v-17.856c0-0.96 0.768-1.728 1.728-1.728h1.888zM23.616 5.344c0.96 0 1.728 0.768 1.728 1.728v17.856c0 0.96-0.768 1.728-1.728 1.728h-1.888c-0.96 0-1.728-0.768-1.728-1.728v-17.856c0-0.96 0.768-1.728 1.728-1.728h1.888z"></path></svg>',
			'justify-center-h'        => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 32 32"><path d="M16 0c0.672 0 1.248 0.512 1.312 1.184l0.032 0.16v29.312c0 0.736-0.608 1.344-1.344 1.344-0.672 0-1.248-0.512-1.312-1.184l-0.032-0.16v-29.312c0-0.736 0.608-1.344 1.344-1.344zM23.616 5.344c0.96 0 1.728 0.768 1.728 1.728v17.856c0 0.96-0.768 1.728-1.728 1.728h-1.888c-0.96 0-1.728-0.768-1.728-1.728v-17.856c0-0.96 0.768-1.728 1.728-1.728h1.888zM10.272 5.344c0.96 0 1.728 0.768 1.728 1.728v17.856c0 0.96-0.768 1.728-1.728 1.728h-1.856c-0.96 0-1.76-0.768-1.76-1.728v-17.856c0-0.96 0.8-1.728 1.76-1.728h1.856z"></path></svg>',
			'justify-end-h'           => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 32 32"><path d="M24 0c0.672 0 1.248 0.512 1.312 1.184l0.032 0.16v29.312c0 0.736-0.608 1.344-1.344 1.344-0.672 0-1.248-0.512-1.312-1.184l-0.032-0.16v-29.312c0-0.736 0.608-1.344 1.344-1.344zM18.272 5.344c0.96 0 1.728 0.768 1.728 1.728v17.856c0 0.96-0.768 1.728-1.728 1.728h-1.856c-0.96 0-1.76-0.768-1.76-1.728v-17.856c0-0.96 0.8-1.728 1.76-1.728h1.856zM10.272 5.344c0.96 0 1.728 0.768 1.728 1.728v17.856c0 0.96-0.768 1.728-1.728 1.728h-1.856c-0.96 0-1.76-0.768-1.76-1.728v-17.856c0-0.96 0.8-1.728 1.76-1.728h1.856z"></path></svg>',
			/**
			 * To Top
			 */
			'top-top-1'               => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 448 512"><path d="M240.971 130.524l194.343 194.343c9.373 9.373 9.373 24.569 0 33.941l-22.667 22.667c-9.357 9.357-24.522 9.375-33.901.04L224 227.495 69.255 381.516c-9.379 9.335-24.544 9.317-33.901-.04l-22.667-22.667c-9.373-9.373-9.373-24.569 0-33.941L207.03 130.525c9.372-9.373 24.568-9.373 33.941-.001z"/></svg>',
			'top-top-2'               => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 448 512"><path d="M34.9 289.5l-22.2-22.2c-9.4-9.4-9.4-24.6 0-33.9L207 39c9.4-9.4 24.6-9.4 33.9 0l194.3 194.3c9.4 9.4 9.4 24.6 0 33.9L413 289.4c-9.5 9.5-25 9.3-34.3-.4L264 168.6V456c0 13.3-10.7 24-24 24h-32c-13.3 0-24-10.7-24-24V168.6L69.2 289.1c-9.3 9.8-24.8 10-34.3.4z"/></svg>',
			'top-top-3'               => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 320 512"><path d="M9.39 265.4l127.1-128C143.6 131.1 151.8 128 160 128s16.38 3.125 22.63 9.375l127.1 128c9.156 9.156 11.9 22.91 6.943 34.88S300.9 320 287.1 320H32.01c-12.94 0-24.62-7.781-29.58-19.75S.2333 274.5 9.39 265.4z"/></svg>',
			'top-top-4'               => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32"><path d="M15.36 6.688c0.352-0.352 0.928-0.352 1.28 0l4.416 4.416c0.576 0.544 0.16 1.472-0.608 1.472h-2.72v16.288c-0.096 0.992-0.832 1.792-1.728 1.792h-0.16c-0.896-0.096-1.568-0.96-1.568-1.984v-16.096h-2.72c-0.768 0-1.184-0.928-0.608-1.472l4.416-4.416zM29.344 0.928c0.96 0 1.728 0.768 1.728 1.728s-0.768 1.728-1.728 1.728h-26.688c-0.96 0-1.728-0.768-1.728-1.728s0.768-1.728 1.728-1.728h26.688z"></path></svg>',
			'top-top-5'               => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 448 512"><path d="M105.6 83.2v86.177a115.52 115.52 0 0 0-22.4-2.176c-47.914 0-83.2 35.072-83.2 92 0 45.314 48.537 57.002 78.784 75.707 12.413 7.735 23.317 16.994 33.253 25.851l.146.131.148.129C129.807 376.338 136 384.236 136 391.2v2.679c-4.952 5.747-8 13.536-8 22.12v64c0 17.673 12.894 32 28.8 32h230.4c15.906 0 28.8-14.327 28.8-32v-64c0-8.584-3.048-16.373-8-22.12V391.2c0-28.688 40-67.137 40-127.2v-21.299c0-62.542-38.658-98.8-91.145-99.94-17.813-12.482-40.785-18.491-62.791-15.985A93.148 93.148 0 0 0 272 118.847V83.2C272 37.765 234.416 0 188.8 0c-45.099 0-83.2 38.101-83.2 83.2zm118.4 0v91.026c14.669-12.837 42.825-14.415 61.05 4.95 19.646-11.227 45.624-1.687 53.625 12.925 39.128-6.524 61.325 10.076 61.325 50.6V264c0 45.491-35.913 77.21-39.676 120H183.571c-2.964-25.239-21.222-42.966-39.596-59.075-12.65-11.275-25.3-21.725-39.875-30.799C80.712 279.645 48 267.994 48 259.2c0-23.375 8.8-44 35.2-44 35.2 0 53.075 26.4 70.4 26.4V83.2c0-18.425 16.5-35.2 35.2-35.2 18.975 0 35.2 16.225 35.2 35.2zM352 424c13.255 0 24 10.745 24 24s-10.745 24-24 24-24-10.745-24-24 10.745-24 24-24z"/></svg>',
			'top-top-6'               => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32"><path d="M23.371 29.529c0 0 0.335-2.012-1.731-4.469 2.011-5.641 2.29-10.778 2.29-10.778s4.133 0.95 4.133 5.026c-0.001 6.981-4.692 10.221-4.692 10.221zM11.979 27.078c0 0-2.768-8.883-2.768-12.568 0-1.658 0.187-3.133 0.478-4.472h12.61c0.293 1.34 0.481 2.816 0.481 4.473 0 3.629-2.76 12.567-2.76 12.567h-8.041zM15.99 12.069c-1.418 0-2.568 1.15-2.568 2.569 0 1.418 1.15 2.569 2.568 2.569s2.569-1.15 2.569-2.569c0.001-1.419-1.15-2.569-2.569-2.569zM15.438 0.596v-3.498h1v3.409c1.143 0.832 4.236 3.478 5.635 8.575h-12.16c1.352-4.957 4.296-7.574 5.525-8.486zM8.629 29.529c0 0-4.691-3.24-4.691-10.221 0-4.076 4.133-5.026 4.133-5.026s0.279 5.137 2.289 10.778c-2.067 2.458-1.731 4.469-1.731 4.469zM17.691 30.045l-0.838-0.838-0.893 2.793-1.062-2.793-0.726 1.451-1.062-2.625h5.752l-1.171 2.012z"></path></svg>',
			/**
			 * Elements
			 */
			'heading'                 => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 448 512"><path d="M448 448c0 17.69-14.33 32-32 32h-96c-17.67 0-32-14.31-32-32s14.33-32 32-32h16v-144h-224v144H128c17.67 0 32 14.31 32 32s-14.33 32-32 32H32c-17.67 0-32-14.31-32-32s14.33-32 32-32h16v-320H32c-17.67 0-32-14.31-32-32s14.33-32 32-32h96c17.67 0 32 14.31 32 32s-14.33 32-32 32H112v112h224v-112H320c-17.67 0-32-14.31-32-32s14.33-32 32-32h96c17.67 0 32 14.31 32 32s-14.33 32-32 32h-16v320H416C433.7 416 448 430.3 448 448z"/></svg>',
			'slides'                  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32"><path d="M25.344 7.328h-18.688c-0.352 0-0.64 0.288-0.64 0.672v13.344c0 0.352 0.288 0.64 0.64 0.64h18.688c0.352 0 0.672-0.288 0.672-0.64v-13.344c0-0.384-0.32-0.672-0.672-0.672zM24.672 20.672h-17.344v-12h17.344v12zM4.48 11.52c-0.256-0.256-0.704-0.256-0.96 0l-2.656 2.688c-0.256 0.256-0.256 0.672 0 0.928l2.656 2.656c0.128 0.128 0.32 0.192 0.48 0.192s0.352-0.064 0.48-0.192c0.256-0.256 0.256-0.672 0-0.928l-2.208-2.208 2.208-2.176c0.256-0.256 0.256-0.704 0-0.96zM31.136 14.208l-2.656-2.688c-0.256-0.256-0.704-0.256-0.96 0s-0.256 0.704 0 0.96l2.208 2.176-2.208 2.208c-0.256 0.256-0.256 0.672 0 0.928 0.128 0.128 0.32 0.192 0.48 0.192s0.352-0.064 0.48-0.192l2.656-2.656c0.256-0.256 0.256-0.672 0-0.928z"></path></svg>',
			'wordpress'               => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512"><path d="M61.7 169.4l101.5 278C92.2 413 43.3 340.2 43.3 256c0-30.9 6.6-60.1 18.4-86.6zm337.9 75.9c0-26.3-9.4-44.5-17.5-58.7-10.8-17.5-20.9-32.4-20.9-49.9 0-19.6 14.8-37.8 35.7-37.8.9 0 1.8.1 2.8.2-37.9-34.7-88.3-55.9-143.7-55.9-74.3 0-139.7 38.1-177.8 95.9 5 .2 9.7.3 13.7.3 22.2 0 56.7-2.7 56.7-2.7 11.5-.7 12.8 16.2 1.4 17.5 0 0-11.5 1.3-24.3 2l77.5 230.4L249.8 247l-33.1-90.8c-11.5-.7-22.3-2-22.3-2-11.5-.7-10.1-18.2 1.3-17.5 0 0 35.1 2.7 56 2.7 22.2 0 56.7-2.7 56.7-2.7 11.5-.7 12.8 16.2 1.4 17.5 0 0-11.5 1.3-24.3 2l76.9 228.7 21.2-70.9c9-29.4 16-50.5 16-68.7zm-139.9 29.3l-63.8 185.5c19.1 5.6 39.2 8.7 60.1 8.7 24.8 0 48.5-4.3 70.6-12.1-.6-.9-1.1-1.9-1.5-2.9l-65.4-179.2zm183-120.7c.9 6.8 1.4 14 1.4 21.9 0 21.6-4 45.8-16.2 76.2l-65 187.9C426.2 403 468.7 334.5 468.7 256c0-37-9.4-71.8-26-102.1zM504 256c0 136.8-111.3 248-248 248C119.2 504 8 392.7 8 256 8 119.2 119.2 8 256 8c136.7 0 248 111.2 248 248zm-11.4 0c0-130.5-106.2-236.6-236.6-236.6C125.5 19.4 19.4 125.5 19.4 256S125.6 492.6 256 492.6c130.5 0 236.6-106.1 236.6-236.6z"/></svg>',
			'magazine-grid'           => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 150 150"><defs><clipPath id="b"><rect width="150" height="150"/></clipPath></defs><g id="a" clip-path="url(#b)"><rect width="83" height="35" transform="translate(10 35)"/><rect width="36.667" height="35" transform="translate(10 80)"/><rect width="37" height="35" transform="translate(103 35)"/><rect width="36.667" height="35" transform="translate(56.667 80)"/><rect width="36.667" height="35" transform="translate(103.333 80)"/></g></svg>',
			'gallery-grid'            => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32"><path d="M9.344 5.984h-8c-0.384 0-0.672 0.32-0.672 0.672v8c0 0.384 0.288 0.672 0.672 0.672h8c0.352 0 0.672-0.288 0.672-0.672v-8c0-0.352-0.32-0.672-0.672-0.672zM8.672 13.984h-6.656v-6.656h6.656v6.656zM20 5.984h-8c-0.352 0-0.672 0.32-0.672 0.672v8c0 0.384 0.32 0.672 0.672 0.672h8c0.384 0 0.672-0.288 0.672-0.672v-8c0-0.352-0.288-0.672-0.672-0.672zM19.328 13.984h-6.656v-6.656h6.656v6.656zM30.656 5.984h-8c-0.352 0-0.64 0.32-0.64 0.672v8c0 0.384 0.288 0.672 0.64 0.672h8c0.384 0 0.672-0.288 0.672-0.672v-8c0-0.352-0.288-0.672-0.672-0.672zM30.016 13.984h-6.688v-6.656h6.688v6.656zM9.344 16.672h-8c-0.384 0-0.672 0.288-0.672 0.672v8c0 0.352 0.288 0.64 0.672 0.64h8c0.352 0 0.672-0.288 0.672-0.64v-8c0-0.384-0.32-0.672-0.672-0.672zM8.672 24.672h-6.656v-6.688h6.656v6.688zM20 16.672h-8c-0.352 0-0.672 0.288-0.672 0.672v8c0 0.352 0.32 0.64 0.672 0.64h8c0.384 0 0.672-0.288 0.672-0.64v-8c0-0.384-0.288-0.672-0.672-0.672zM19.328 24.672h-6.656v-6.688h6.656v6.688zM30.656 16.672h-8c-0.352 0-0.64 0.288-0.64 0.672v8c0 0.352 0.288 0.64 0.64 0.64h8c0.384 0 0.672-0.288 0.672-0.64v-8c0-0.384-0.288-0.672-0.672-0.672zM30.016 24.672h-6.688v-6.688h6.688v6.688z"></path></svg>',
			'posts-grid'              => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32"><path d="M9.344 2.656h-8c-0.384 0-0.672 0.32-0.672 0.672v8c0 0.384 0.288 0.672 0.672 0.672h8c0.352 0 0.672-0.288 0.672-0.672v-8c0-0.352-0.32-0.672-0.672-0.672zM8.672 10.656h-6.656v-6.656h6.656v6.656zM8 14.656c0.384 0 0.672-0.288 0.672-0.672s-0.288-0.64-0.672-0.64h-5.344c-0.352 0-0.64 0.288-0.64 0.64s0.288 0.672 0.64 0.672h5.344zM20 2.656h-8c-0.352 0-0.672 0.32-0.672 0.672v8c0 0.384 0.32 0.672 0.672 0.672h8c0.384 0 0.672-0.288 0.672-0.672v-8c0-0.352-0.288-0.672-0.672-0.672zM19.328 10.656h-6.656v-6.656h6.656v6.656zM18.656 14.656c0.384 0 0.672-0.288 0.672-0.672s-0.288-0.64-0.672-0.64h-5.312c-0.384 0-0.672 0.288-0.672 0.64s0.288 0.672 0.672 0.672h5.312zM30.656 2.656h-8c-0.352 0-0.64 0.32-0.64 0.672v8c0 0.384 0.288 0.672 0.64 0.672h8c0.384 0 0.672-0.288 0.672-0.672v-8c0-0.352-0.288-0.672-0.672-0.672zM30.016 10.656h-6.688v-6.656h6.688v6.656zM29.344 14.656c0.352 0 0.672-0.288 0.672-0.672s-0.32-0.64-0.672-0.64h-5.344c-0.352 0-0.672 0.288-0.672 0.64s0.32 0.672 0.672 0.672h5.344zM9.344 17.344h-8c-0.384 0-0.672 0.288-0.672 0.64v8c0 0.384 0.288 0.672 0.672 0.672h8c0.352 0 0.672-0.288 0.672-0.672v-8c0-0.352-0.32-0.64-0.672-0.64zM8.672 25.344h-6.656v-6.688h6.656v6.688zM8 28h-5.344c-0.352 0-0.64 0.288-0.64 0.672s0.288 0.672 0.64 0.672h5.344c0.384 0 0.672-0.32 0.672-0.672s-0.288-0.672-0.672-0.672zM20 17.344h-8c-0.352 0-0.672 0.288-0.672 0.64v8c0 0.384 0.32 0.672 0.672 0.672h8c0.384 0 0.672-0.288 0.672-0.672v-8c0-0.352-0.288-0.64-0.672-0.64zM19.328 25.344h-6.656v-6.688h6.656v6.688zM18.656 28h-5.312c-0.384 0-0.672 0.288-0.672 0.672s0.288 0.672 0.672 0.672h5.312c0.384 0 0.672-0.32 0.672-0.672s-0.288-0.672-0.672-0.672zM30.656 17.344h-8c-0.352 0-0.64 0.288-0.64 0.64v8c0 0.384 0.288 0.672 0.64 0.672h8c0.384 0 0.672-0.288 0.672-0.672v-8c0-0.352-0.288-0.64-0.672-0.64zM30.016 25.344h-6.688v-6.688h6.688v6.688zM29.344 28h-5.344c-0.352 0-0.672 0.288-0.672 0.672s0.32 0.672 0.672 0.672h5.344c0.352 0 0.672-0.32 0.672-0.672s-0.32-0.672-0.672-0.672z"></path></svg>',
			'post-list'               => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32"><path d="M8.672 0.672h-8c-0.384 0-0.672 0.288-0.672 0.672v8c0 0.352 0.288 0.64 0.672 0.64h8c0.352 0 0.672-0.288 0.672-0.64v-8c0-0.384-0.32-0.672-0.672-0.672zM8 8.672h-6.656v-6.688h6.656v6.688zM12.672 5.984h18.656c0.384 0 0.672-0.288 0.672-0.64s-0.288-0.672-0.672-0.672h-18.656c-0.384 0-0.672 0.288-0.672 0.672s0.288 0.64 0.672 0.64zM12.672 3.328h8c0.352 0 0.672-0.288 0.672-0.672s-0.32-0.672-0.672-0.672h-8c-0.384 0-0.672 0.32-0.672 0.672s0.288 0.672 0.672 0.672zM31.328 7.328h-18.656c-0.384 0-0.672 0.288-0.672 0.672s0.288 0.672 0.672 0.672h18.656c0.384 0 0.672-0.32 0.672-0.672s-0.288-0.672-0.672-0.672zM8.672 11.328h-8c-0.384 0-0.672 0.288-0.672 0.672v8c0 0.352 0.288 0.672 0.672 0.672h8c0.352 0 0.672-0.32 0.672-0.672v-8c0-0.384-0.32-0.672-0.672-0.672zM8 19.328h-6.656v-6.656h6.656v6.656zM31.328 15.328h-18.656c-0.384 0-0.672 0.288-0.672 0.672s0.288 0.672 0.672 0.672h18.656c0.384 0 0.672-0.32 0.672-0.672s-0.288-0.672-0.672-0.672zM12.672 13.984h8c0.352 0 0.672-0.288 0.672-0.64s-0.32-0.672-0.672-0.672h-8c-0.384 0-0.672 0.288-0.672 0.672s0.288 0.64 0.672 0.64zM31.328 17.984h-18.656c-0.384 0-0.672 0.32-0.672 0.672s0.288 0.672 0.672 0.672h18.656c0.384 0 0.672-0.288 0.672-0.672s-0.288-0.672-0.672-0.672zM8.672 21.984h-8c-0.384 0-0.672 0.32-0.672 0.672v8c0 0.384 0.288 0.672 0.672 0.672h8c0.352 0 0.672-0.288 0.672-0.672v-8c0-0.352-0.32-0.672-0.672-0.672zM8 30.016h-6.656v-6.688h6.656v6.688zM31.328 25.984h-18.656c-0.384 0-0.672 0.32-0.672 0.672s0.288 0.672 0.672 0.672h18.656c0.384 0 0.672-0.288 0.672-0.672s-0.288-0.672-0.672-0.672zM12.672 24.672h8c0.352 0 0.672-0.32 0.672-0.672s-0.32-0.672-0.672-0.672h-8c-0.384 0-0.672 0.288-0.672 0.672s0.288 0.672 0.672 0.672zM31.328 28.672h-18.656c-0.384 0-0.672 0.288-0.672 0.672s0.288 0.672 0.672 0.672h18.656c0.384 0 0.672-0.32 0.672-0.672s-0.288-0.672-0.672-0.672z"></path></svg>',
			/**
			 * Others
			 */
			'lock'                    => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 448 512"><path d="M80 192V144C80 64.47 144.5 0 224 0C303.5 0 368 64.47 368 144V192H384C419.3 192 448 220.7 448 256V448C448 483.3 419.3 512 384 512H64C28.65 512 0 483.3 0 448V256C0 220.7 28.65 192 64 192H80zM144 192H304V144C304 99.82 268.2 64 224 64C179.8 64 144 99.82 144 144V192z"/></svg>',
		];

		if ( ! isset( $svgs[ $name ] ) ) {
			return '';
		}

		return $svgs[ $name ];
	}
}

if ( ! function_exists( 'yuki_image_url' ) ) {
	/**
	 * Get image file url
	 *
	 * @param $path
	 *
	 * @return string
	 */
	function yuki_image_url( $path ): string {
		return trailingslashit( get_template_directory_uri() ) . 'dist/images/' . $path;
	}
}

if ( ! function_exists( 'yuki_container_css' ) ) {
	/**
	 * Get container css
	 *
	 * @param string $layout
	 * @param array $css
	 *
	 * @return []|array|string[]
	 */
	function yuki_container_css( $layout = 'no-sidebar', $css = [] ) {
		return array_merge( $css, [
			'yuki-container container mx-auto px-gutter lg:flex',
			'yuki-no-sidebar'                       => $layout !== 'right-sidebar' && $layout !== 'left-sidebar',
			'yuki-right-sidebar lg:flex-row'        => $layout === 'right-sidebar',
			'yuki-left-sidebar lg:flex-row-reverse' => $layout === 'left-sidebar',
		] );
	}
}

if ( ! function_exists( 'yuki_kses' ) ) {
	/**
	 * Kses function support svg
	 *
	 * @param $data
	 *
	 * @return string
	 */
	function yuki_kses( $data ) {
		$kses_defaults = wp_kses_allowed_html( 'post' );

		// add svg support
		$svg_args = array(
			'svg'      => array(
				'class'           => true,
				'aria-hidden'     => true,
				'aria-labelledby' => true,
				'role'            => true,
				'xmlns'           => true,
				'width'           => true,
				'height'          => true,
				'viewbox'         => true,
			),
			'clipPath' => array( 'id' => true ),
			'rect'     => array( 'width' => true, 'height' => true, 'fill' => true, 'transform' => true ),
			'defs'     => array(),
			'g'        => array( 'fill' => true ),
			'title'    => array( 'title' => true ),
			'path'     => array( 'd' => true, 'fill' => true, ),
		);

		return wp_kses( $data, array_merge( $kses_defaults, $svg_args ) );
	}
}

if ( ! function_exists( 'yuki_scroll_reveal_args' ) ) {
	/**
	 * Scroll reveal args
	 *
	 * @return array
	 */
	function yuki_scroll_reveal_args() {
		return [
			'delay'    => absint( CZ::get( 'yuki_scroll_reveal_delay' ) ),
			'duration' => absint( CZ::get( 'yuki_scroll_reveal_duration' ) ),
			'interval' => absint( CZ::get( 'yuki_scroll_reveal_interval' ) ),
			'opacity'  => floatval( CZ::get( 'yuki_scroll_reveal_opacity' ) ),
			'scale'    => floatval( CZ::get( 'yuki_scroll_reveal_scale' ) ),
			'origin'   => CZ::get( 'yuki_scroll_reveal_origin' ),
			'distance' => CZ::get( 'yuki_scroll_reveal_distance' ),
		];
	}
}

if ( ! function_exists( 'yuki_post_metas' ) ) {
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 *
	 * @param $id
	 * @param array|string[] $items
	 * @param array $args
	 * @param null $options
	 * @param array $settings
	 */
	function yuki_post_metas(
		$id, array $items = [
		'posted_on',
		'views',
		'comments'
	], $args = [], $options = null, $settings = []
	) {
		$default_args = [
			'before' => '',
			'after'  => '',
			'sep'    => '',
			'style'  => '',
		];

		$options = $options ?? CZ::getFacadeRoot();

		extract( array_merge( $default_args, $args ) );
		$date_format = $options->get( 'yuki_' . $id . '_published_format', $settings );
		$divider     = $options->get( 'yuki_' . $id . '_meta_items_divider', $settings );
		$icon        = $options->get( 'yuki_' . $id . '_meta_items_style', $settings ) === 'icon';

		echo $before;

		foreach ( $items as $item ) {

			if ( $item === 'byline' ) {

				$byline = sprintf(
					'%s',
					'<a class="' . $style . '" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>'
				);

				if ( ! empty( $byline ) ) {
					echo '<span class="byline meta-item"> ' . ( $icon ? IconsManager::render( $options->get( 'yuki_' . $id . '_byline_icon' ) ) : '' ) . $byline . '</span>';
				}
			} elseif ( $item === 'published' ) {

				$time_string = '<time class="published updated" datetime="%1$s">%2$s</time>';
				if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
					$time_string = '<time class="published" datetime="%1$s">%2$s</time><time class="updated hidden" datetime="%3$s">%4$s</time>';
				}

				$time_string = sprintf( $time_string,
					esc_attr( get_the_date( 'c' ) ),
					esc_html( get_the_date( $date_format ) ),
					esc_attr( get_the_modified_date( 'c' ) ),
					esc_html( get_the_modified_date( $date_format ) )
				);

				$posted_on = sprintf(
					'%s',
					'<a class="' . $style . '" href="' . esc_url( get_permalink() ) . '" rel="bookmark"><span class="entry-date">' . $time_string . '</span></a>'
				);

				if ( ! empty( $posted_on ) ) {
					echo '<span class="meta-item posted-on">' . ( $icon ? IconsManager::render( $options->get( 'yuki_' . $id . '_published_icon' ) ) : '' ) . $posted_on . '</span>';
				}
			} elseif ( $item === 'views' ) {
				$views = yuki_post_views();
				if ( $views <= 0 ) {
					continue;
				}
				echo '<span class="meta-item views">' . ( $icon ? IconsManager::render( $options->get( 'yuki_' . $id . '_views_icon' ) ) : '' ) . $views . '</span>';
			} elseif ( $item === 'comments' ) {
				if ( ! comments_open( get_the_ID() ) || get_comments_number() <= 0 ) {
					continue;
				}

				echo '<span class="meta-item comments-link">';
				echo $icon ? IconsManager::render( $options->get( 'yuki_' . $id . '_comments_icon' ) ) : '';
				comments_popup_link( false, false, false, $style );
				echo '</span>';
			}

			if ( $divider !== 'none' ) {
				echo '<span class="meta-divider">';
				echo yuki_image( $divider );
				echo '</span>';
			} else {
				echo '<span class="meta-empty-divider mr-2"></span>';
			}
		}

		echo $after;
	}
}

if ( ! function_exists( 'yuki_post_categories' ) ) {
	/**
	 * Prints HTML with categories information for the current post.
	 *
	 * @param string $before
	 * @param string $after
	 * @param array $style
	 */
	function yuki_post_categories( $before = '', $after = '', $style = [] ) {
		// Hide category for pages.
		if ( 'post' !== get_post_type() || empty( get_the_category() ) ) {
			return;
		}

		global $wp_rewrite;

		$style = esc_attr( Utils::clsx( $style ) );

		$rel = ( is_object( $wp_rewrite ) && $wp_rewrite->using_permalinks() ) ? 'rel="category tag"' : 'rel="category"';
		echo $before;
		foreach ( get_the_category() as $category ) {
			echo '<a class="' . $style . '" href="' . esc_url( get_category_link( $category->term_id ) ) . '" ' . $rel . '>' . esc_html( $category->name ) . '</a>';
		}
		echo $after;
	}
}

if ( ! function_exists( 'yuki_post_tags' ) ) {
	/**
	 * Prints HTML with tags information for the current post.
	 *
	 * @param string $before
	 * @param string $after
	 * @param array $style
	 */
	function yuki_post_tags( $before = '', $after = '', $style = [] ) {
		// Hide tag text for pages.
		if ( 'post' !== get_post_type() ) {
			return;
		}

		$tags = get_the_tags();

		if ( is_wp_error( $tags ) || empty( $tags ) ) {
			return;
		}

		$style = esc_attr( Utils::clsx( $style ) );

		$tag_links = array_map( function ( $tag ) use ( $style ) {
			return '<a class="' . $style . '" href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" rel="tag">' . $tag->name . '</a>';
		}, $tags );

		/* Translators: used between list items, there is a space after the comma. */
		echo $before . implode( '', $tag_links ) . $after;
	}
}

if ( ! function_exists( 'yuki_post_views' ) ) {
	/**
	 * Post views count
	 *
	 * @param int $post_id
	 *
	 * @return int
	 */
	function yuki_post_views( $post_id = 0 ) {
		if ( function_exists( 'pvc_get_post_views' ) ) {
			return pvc_get_post_views( $post_id );
		}

		return - 1;
	}
}

if ( ! function_exists( 'yuki_post_structure' ) ) {
	/**
	 * Render post structure
	 */
	function yuki_post_structure( $id, $structure, $metas, $args = [] ) {
		$args = wp_parse_args( $args, [
			'title_link'   => false,
			'title_tag'    => 'h1',
			'excerpt_type' => 'full',
			'options'      => CZ::getFacadeRoot(),
			'settings'     => [],
		] );

		$options    = $args['options'];
		$settings   = $args['settings'];
		$title_link = $args['title_link'];
		$title_tag  = $args['title_tag'];

		yuki_app()->instance( 'store.excerpt_more_text', (string) $options->get( 'yuki_' . $id . '_excerpt_more_text', $settings ) );
		yuki_app()->instance( 'store.excerpt_length', (string) $options->get( 'yuki_' . $id . '_excerpt_length', $settings ) );

		$content_open = false;
		?>
		<?php foreach ( $structure as $item ): ?>
			<?php if ( $item === 'thumbnail' && has_post_thumbnail() ): ?>
				<?php
				if ( $content_open ) {
					$content_open = false;
					echo '</div>';
				}
				?>

                <a href="<?php the_permalink() ?>" class="card-thumbnail entry-thumbnail last:mb-0">
					<?php the_post_thumbnail( 'yuki-thumbnail', [
						'class' => 'w-full h-full',
					] ); ?>
                </a>
			<?php else: ?>
				<?php
				if ( ! $content_open ) {
					$content_open = true;
					echo '<div class="card-content flex-grow">';
				}
				?>
			<?php endif; ?>
			<?php if ( $item === 'title' && ! $title_link ): ?>
                <div class="entry-title mb-gutter last:mb-0">
					<?php the_title( "<$title_tag>", "</$title_tag>" ); ?>
                </div>
			<?php endif; ?>
			<?php if ( $item === 'title' && $title_link ): ?>
				<?php
				echo wp_kses_post( sprintf(
					'<%1$s class="entry-title mb-half-gutter last:mb-0">%2$s %3$s</%1$s>',
					$title_tag,
					sprintf(
						'<a class="link" href="%1$s" rel="bookmark">%2$s</a>',
						esc_url( get_permalink() ),
						get_the_title()
					),
					( get_edit_post_link() ? sprintf(
						'<span class="link text-xs font-normal"><a href="%1$s">%2$s</a></span>',
						get_edit_post_link(),
						__( 'Edit', 'yuki' )
					) : '' )
				) );
				?>
			<?php endif; ?>
			<?php if ( $item === 'metas' ): ?>
                <div class="entry-metas mb-half-gutter last:mb-0">
					<?php yuki_post_metas( $id, $metas, [
						'style' => 'entry-meta-link',
					], $options, $settings ); ?>
                </div>
			<?php endif; ?>
			<?php if ( $item === 'categories' ): ?>
				<?php
				yuki_post_categories(
					'<div class="entry-categories cat-taxonomies break-words mb-2 last:mb-0" data-tax-type="' . $options->get( 'yuki_' . $id . '_tax_style_cats', $settings ) . '">',
					'</div>',
					[ 'entry-tax-item mr-2 last:mr-0' ]
				);
				?>
			<?php endif; ?>

			<?php if ( $item === 'tags' ): ?>
				<?php
				yuki_post_tags(
					'<div class="entry-tags cat-taxonomies mb-2 break-words last:mb-0" data-tax-type="' . $options->get( 'yuki_' . $id . '_tax_style_tags', $settings ) . '">',
					'</div>',
					[ 'entry-tax-item mr-2 last:mr-0' ]
				);
				?>
			<?php endif; ?>
			<?php if ( $item === 'excerpt' ): ?>
                <div class="entry-excerpt mb-gutter last:mb-0">
					<?php
					if ( $args['excerpt_type'] === 'full' ) {
						echo get_the_content();
					} else {
						echo get_the_excerpt();
					}
					?>
                </div>
			<?php endif; ?>
			<?php if ( $item === 'read-more' ): ?>
                <div class="mb-gutter last:mb-0">
                    <a class="yuki-button entry-read-more" href="<?php the_permalink() ?>" rel="bookmark">
						<?php esc_html_e( 'Read More', 'yuki' ); ?>
                    </a>
                </div>
			<?php endif; ?>
			<?php if ( $item === 'divider' ): ?>
                <div class="entry-divider"></div>
			<?php endif; ?>
		<?php endforeach; ?>
		<?php
	}
}

if ( ! function_exists( 'yuki_post_elements_css' ) ) {
	/**
	 * Generate dynamic css for post elements
	 *
	 * @param $scope_selector
	 * @param $id
	 * @param $elements
	 * @param null $options
	 * @param array $settings
	 *
	 * @return array
	 */
	function yuki_post_elements_css( $scope_selector, $id, $elements, $options = null, array $settings = [] ) {
		$options = $options ?? CZ::getFacadeRoot();
		$css     = [];

		foreach ( $elements as $element ) {

			// title
			if ( $element === 'title' ) {
				$css["$scope_selector .entry-title"] = array_merge(
					Css::typography( $options->get( 'yuki_' . $id . '_title_typography', $settings ) ),
					Css::colors( $options->get( 'yuki_' . $id . '_title_color', $settings ), [
						'initial' => '--yuki-initial-color',
						'hover'   => '--yuki-hover-color',
					] ) );
			}

			// taxonomies
			if ( $element === 'categories' || $element === 'tags' ) {
				$tax      = $element === 'categories' ? '_cats' : '_tags';
				$selector = ".entry-{$element}";

				$tax_css  = Css::typography( $options->get( 'yuki_' . $id . '_tax_typography' . $tax, $settings ) );
				$tax_type = $options->get( 'yuki_' . $id . '_tax_style' . $tax, $settings );

				if ( $tax_type === 'default' ) {
					$tax_css = array_merge(
						$tax_css,
						Css::colors( $options->get( 'yuki_' . $id . '_tax_default_color' . $tax, $settings ), [
							'initial' => '--yuki-tax-text-initial',
							'hover'   => '--yuki-tax-text-hover',
						] )
					);
				} else {
					$tax_css = array_merge(
						$tax_css,
						Css::colors( $options->get( 'yuki_' . $id . '_tax_badge_text_color' . $tax, $settings ), [
							'initial' => '--yuki-tax-text-initial',
							'hover'   => '--yuki-tax-text-hover',
						] ),
						Css::colors( $options->get( 'yuki_' . $id . '_tax_badge_bg_color' . $tax, $settings ), [
							'initial' => '--yuki-tax-bg-initial',
							'hover'   => '--yuki-tax-bg-hover',
						] )
					);
				}

				$css["$scope_selector $selector"] = $tax_css;
			}

			// excerpt
			if ( $element === 'excerpt' ) {
				$css["$scope_selector .entry-excerpt"] = array_merge(
					Css::typography( $options->get( 'yuki_' . $id . '_excerpt_typography', $settings ) ),
					Css::colors( $options->get( 'yuki_' . $id . '_excerpt_color', $settings ), [
						'initial' => 'color'
					] )
				);
			}

			// divider
			if ( $element === 'divider' ) {
				$css["$scope_selector .entry-divider"] = Css::border(
					$options->get( 'yuki_' . $id . '_divider', $settings ),
					'--entry-divider'
				);
			}

			// metas
			if ( $element === 'metas' ) {
				$css["$scope_selector .entry-metas"] = array_merge(
					Css::typography( $options->get( 'yuki_' . $id . '_meta_typography', $settings ) ),
					Css::colors( $options->get( 'yuki_' . $id . '_meta_color', $settings ), [
						'initial' => '--yuki-meta-link-initial-color',
						'hover'   => '--yuki-meta-link-hover-color',
					] )
				);
			}

			// thumbnail
			if ( $element === 'thumbnail' ) {
				$css["$scope_selector .entry-thumbnail"] = array_merge(
					[ 'height' => CZ::get( 'yuki_' . $id . '_thumbnail_height' ) ],
					Css::dimensions( CZ::get( 'yuki_' . $id . '_thumbnail_radius' ), 'border-radius' ),
					Css::shadow( CZ::get( 'yuki_' . $id . '_thumbnail_shadow' ) )
				);
			}

			if ( $element === 'read-more' ) {
				$css["$scope_selector .entry-read-more"] = array_merge(
					[
						'--yuki-button-height' => CZ::get( 'yuki_' . $id . '_read_more_min_height' )
					],
					Css::typography( CZ::get( 'yuki_' . $id . '_read_more_typography' ) ),
					Css::dimensions( CZ::get( 'yuki_' . $id . '_read_more_padding' ), '--yuki-button-padding' ),
					Css::dimensions( CZ::get( 'yuki_' . $id . '_read_more_radius' ), '--yuki-button-radius' ),
					Css::colors( CZ::get( 'yuki_' . $id . '_read_more_text_color' ), [
						'initial' => '--yuki-button-text-initial-color',
						'hover'   => '--yuki-button-text-hover-color',
					] ),
					Css::colors( CZ::get( 'yuki_' . $id . '_read_more_button_color' ), [
						'initial' => '--yuki-button-initial-color',
						'hover'   => '--yuki-button-hover-color',
					] ),
					Css::border( CZ::get( 'yuki_' . $id . '_read_more_border' ), '--yuki-button-border' )
				);
			}
		}

		return $css;
	}
}

if ( ! function_exists( 'yuki_show_article_feature_image' ) ) {
	/**
	 * Show feature image
	 *
	 * @param string $preview_location
	 * @param $prefix
	 */
	function yuki_show_article_feature_image( $preview_location, $prefix ) {

		$thumb_attrs = [
			'class' => 'article-featured-image prose prose-yuki mx-auto',
		];

		if ( is_customize_preview() ) {
			$thumb_attrs['data-shortcut']          = 'border';
			$thumb_attrs['data-shortcut-location'] = $preview_location . ':' . $prefix . '_featured_image';
		}

		if ( has_post_thumbnail() ) {
			$width = CZ::get( $prefix . '_featured_image_width' );

			echo '<div ' . Utils::render_attribute_string( $thumb_attrs ) . '>';
			the_post_thumbnail(
				CZ::get( $prefix . '_featured_image_size' ),
				array(
					'class' => Utils::clsx( [
						'h-full object-center object-cover',
						'w-full'    => $width === 'default',
						'alignwide' => $width === 'wide',
						'alignfull' => $width === 'full',
					] )
				) );
			echo '</div>';
		}
	}
}

if ( ! function_exists( 'yuki_show_article_header' ) ) {
	/**
	 * Show article header
	 *
	 * @param $preview_location
	 * @param string $type
	 * @param bool $header
	 */
	function yuki_show_article_header( $preview_location, string $type = 'post', $header = true ) {

		$prefix          = 'yuki_' . $type;
		$header_elements = CZ::layers( $prefix . '_header_elements' );

		$has_featured_image = CZ::checked( "{$prefix}_featured_image" );
		$featured_image_pos = CZ::get( "{$prefix}_featured_image_position" );

		$header_attrs = [
			'class' => 'yuki-article-header yuki-max-w-content mx-auto relative z-[1]',
		];

		if ( is_customize_preview() ) {
			$header_attrs['data-shortcut']          = 'border';
			$header_attrs['data-shortcut-location'] = "$preview_location:{$prefix}_header";
		}

		?>
		<?php
		if ( $has_featured_image ) {
			if ( $featured_image_pos === 'above' ) {
				yuki_show_article_feature_image( $preview_location, $prefix );
			}

			if ( $featured_image_pos === 'behind' ) {

				$background_attrs = [
					'class' => 'yuki-article-header-background alignfull mb-gutter',
					'style' => 'background-image: url("' . esc_url( get_the_post_thumbnail_url() ) . '")',
				];

				if ( is_customize_preview() ) {
					$background_attrs['data-shortcut']          = 'border';
					$background_attrs['data-shortcut-location'] = $preview_location . ':' . $prefix . '_featured_image';
				}

				echo '<div ' . Utils::render_attribute_string( $background_attrs ) . '>';
			}
		}
		?>

		<?php if ( $header && ! empty( $header_elements ) && CZ::checked( "{$prefix}_header" ) ): ?>
            <header <?php Utils::print_attribute_string( $header_attrs ); ?>>
				<?php
				yuki_post_structure( $type, $header_elements, CZ::layers( "{$prefix}_metas" ), [
					'title_link' => false,
					'title_tag'  => CZ::get( "{$prefix}_title_tag" ),
				] );
				?>
            </header>
		<?php endif; ?>

		<?php
		if ( $has_featured_image ) {
			if ( $featured_image_pos === 'behind' ) {
				echo '</div>';
			}

			if ( $featured_image_pos === 'below' ) {
				yuki_show_article_feature_image( $preview_location, $prefix );
			}
		}
		?>
		<?php
	}
}

if ( ! function_exists( 'yuki_show_article' ) ) {
	/**
	 * Show article content
	 *
	 * @param $preview_location
	 * @param string $type
	 * @param bool $header
	 */
	function yuki_show_article( $preview_location, string $type = 'post', $header = true ) {
		?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<?php
			if ( CZ::get( "yuki_{$type}_featured_image_position" ) !== 'behind' ) {
				yuki_show_article_header( $preview_location, $type, $header );
			}
			?>

            <!-- Article Content -->
            <div class="yuki-article-content yuki-entry-content clearfix mx-auto prose prose-yuki">

				<?php

				the_content();

				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'yuki' ),
					'after'  => '</div>',
				) );
				?>

                <div class="text-base link inline">
					<?php edit_post_link( esc_html__( 'Edit', 'yuki' ) ); ?>
                </div>
            </div>
        </article>
		<?php
	}
}

if ( ! function_exists( 'yuki_get_shapes' ) ) {
	/**
	 * Get all shapes
	 */
	function yuki_get_shapes( $shape = null ) {
		$shapes = array(
			'none'                  => array(
				'title'   => _x( 'None', 'Shapes', 'yuki' ),
				'options' => array(),
			),
			'tilt'                  => array(
				'title'   => _x( 'Tilt', 'Shapes', 'yuki' ),
				'options' => array( 'shape_flip' ),
			),
			'mountains'             => array(
				'title'   => _x( 'Mountains (Pro)', 'Shapes', 'yuki' ),
				'folder'  => 'images/shapes__premium_only',
				'options' => array( 'shape_flip' ),
			),
			'drops'                 => array(
				'title'   => _x( 'Drops  (Pro)', 'Shapes', 'yuki' ),
				'folder'  => 'images/shapes__premium_only',
				'options' => array( 'shape_flip', 'shape_invert' ),
			),
			'clouds'                => array(
				'title'   => _x( 'Clouds (Pro)', 'Shapes', 'yuki' ),
				'folder'  => 'images/shapes__premium_only',
				'options' => array( 'shape_flip', 'shape_invert' ),
			),
			'zigzag'                => array(
				'title'   => _x( 'Zigzag (Pro)', 'Shapes', 'yuki' ),
				'folder'  => 'images/shapes__premium_only',
				'options' => array(),
			),
			'pyramids'              => array(
				'title'   => _x( 'Pyramids (Pro)', 'Shapes', 'yuki' ),
				'folder'  => 'images/shapes__premium_only',
				'options' => array( 'shape_flip', 'shape_invert' ),
			),
			'triangle'              => array(
				'title'   => _x( 'Triangle (Pro)', 'Shapes', 'yuki' ),
				'folder'  => 'images/shapes__premium_only',
				'options' => array( 'shape_invert' ),
			),
			'triangle-asymmetrical' => array(
				'title'   => _x( 'Triangle Asymmetrical (Pro)', 'Shapes', 'yuki' ),
				'folder'  => 'images/shapes__premium_only',
				'options' => array( 'shape_flip', 'shape_invert' ),
			),
			'opacity-tilt'          => array(
				'title'   => _x( 'Tilt Opacity (Pro)', 'Shapes', 'yuki' ),
				'folder'  => 'images/shapes__premium_only',
				'options' => array( 'shape_flip' ),
			),
			'opacity-fan'           => array(
				'title'   => _x( 'Fan Opacity (Pro)', 'Shapes', 'yuki' ),
				'folder'  => 'images/shapes__premium_only',
				'options' => array(),
			),
			'curve'                 => array(
				'title'   => _x( 'Curve (Pro)', 'Shapes', 'yuki' ),
				'folder'  => 'images/shapes__premium_only',
				'options' => array( 'shape_invert' ),
			),
			'curve-asymmetrical'    => array(
				'title'   => _x( 'Curve Asymmetrical (Pro)', 'Shapes', 'yuki' ),
				'folder'  => 'images/shapes__premium_only',
				'options' => array( 'shape_flip', 'shape_invert' ),
			),
			'waves'                 => array(
				'title'   => _x( 'Waves (Pro)', 'Shapes', 'yuki' ),
				'folder'  => 'images/shapes__premium_only',
				'options' => array( 'shape_flip', 'shape_invert' ),
			),
			'wave-brush'            => array(
				'title'   => _x( 'Waves Brush (Pro)', 'Shapes', 'yuki' ),
				'folder'  => 'images/shapes__premium_only',
				'options' => array( 'shape_flip' ),
			),
			'waves-pattern'         => array(
				'title'   => _x( 'Waves Pattern (Pro)', 'Shapes', 'yuki' ),
				'folder'  => 'images/shapes__premium_only',
				'options' => array( 'shape_flip' ),
			),
			'arrow'                 => array(
				'title'   => _x( 'Arrow (Pro)', 'Shapes', 'yuki' ),
				'folder'  => 'images/shapes__premium_only',
				'options' => array( 'shape_invert' ),
			),
			'split'                 => array(
				'title'   => _x( 'Split (Pro)', 'Shapes', 'yuki' ),
				'folder'  => 'images/shapes__premium_only',
				'options' => array( 'shape_invert' ),
			),
			'book'                  => array(
				'title'   => _x( 'Book (Pro)', 'Shapes', 'yuki' ),
				'folder'  => 'images/shapes__premium_only',
				'options' => array( 'shape_invert' ),
			),
		);

		if ( $shape !== null ) {
			return isset( $shapes[ $shape ] ) ? $shapes[ $shape ] : null;
		}

		return $shapes;
	}
}

if ( ! function_exists( 'yuki_image_attr' ) ) {
	/**
	 * Get image attr
	 *
	 * @param $image
	 *
	 * @return array
	 */
	function yuki_image_attr( $image ) {
		$image_attr = [];

		if ( isset( $image['url'] ) && ! empty( $image['url'] ) ) {
			$attachment_id     = $image['attachment_id'] ?? 0;
			$alt_attribute     = $attachment_id <= 0 ? [] : get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
			$alt_attribute     = ! empty( $alt_attribute ) ? $alt_attribute : get_bloginfo( 'name' );
			$image_attr['src'] = esc_url( $image['url'] ?? '' );
			$image_attr['alt'] = esc_attr( $alt_attribute );
		}

		return $image_attr;
	}
}
