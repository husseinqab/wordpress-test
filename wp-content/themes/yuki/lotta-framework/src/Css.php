<?php

namespace LottaFramework;

use LottaFramework\Typography\Fonts;

class Css {

	/**
	 * Breakpoints for media query
	 *
	 * @var array|mixed
	 */
	protected $breakpoints;

	/**
	 * @param array $breakpoints
	 */
	public function __construct( array $breakpoints = [] ) {
		$this->setBreakpoints( $breakpoints );
	}

	/**
	 * Set responsive breakpoints
	 *
	 * @param array $breakpoints
	 */
	public function setBreakpoints( $breakpoints = [] ) {
		$this->breakpoints = wp_parse_args( $breakpoints, [
			'desktop' => '1024px',
			'tablet'  => '768px',
			'mobile'  => '576px',
		] );
	}

	public function desktop() {
		return $this->breakpoints['desktop'] ?? '';
	}

	public function tablet() {
		return $this->breakpoints['tablet'] ?? '';
	}

	public function mobile() {
		return $this->breakpoints['mobile'] ?? '';
	}

	/**
	 * Parse css output
	 *
	 * @param array $css_output
	 * @param bool $beauty
	 *
	 * @return string Generated CSS.
	 */
	public function parse( $css_output = [], $beauty = false ) {

		$parse_css      = '';
		$tablet_output  = [];
		$desktop_output = [];
		$eol            = $beauty ? PHP_EOL : '';

		if ( ! is_array( $css_output ) || count( $css_output ) <= 0 ) {
			return $parse_css;
		}

		foreach ( $css_output as $selector => $properties ) {

			if ( null === $properties ) {
				break;
			}

			if ( ! count( $properties ) ) {
				continue;
			}

			$temp_parse_css      = $selector . '{' . $eol;
			$temp_tablet_output  = [];
			$temp_desktop_output = [];
			$properties_added    = 0;

			foreach ( $properties as $property => $value ) {

				// responsive value
				if ( is_array( $value ) ) {
					$temp_tablet_output[ $property ]  = $value['tablet'] ?? '';
					$temp_desktop_output[ $property ] = $value['desktop'] ?? '';

					$value = $value['mobile'] ?? '';
				}

				if ( '' === $value || null === $value ) {
					continue;
				}

				$properties_added ++;

				$temp_parse_css .= $property . ':' . $value . ';' . $eol;
			}

			$temp_parse_css .= '}';

			if ( ! empty( $temp_tablet_output ) ) {
				$tablet_output[ $selector ] = $temp_tablet_output;
			}

			if ( ! empty( $temp_desktop_output ) ) {
				$desktop_output[ $selector ] = $temp_desktop_output;
			}

			if ( $properties_added > 0 ) {
				$parse_css .= $temp_parse_css;
			}
		}

		$tablet_css = $this->parse( $tablet_output, $beauty );
		if ( $tablet_css !== '' && isset( $this->breakpoints['tablet'] ) ) {
			$tablet_css = '@media (min-width: ' . $this->breakpoints['tablet'] . ') {' . $eol . $tablet_css . $eol . '}' . $eol;
		}

		$desktop_css = $this->parse( $desktop_output, $beauty );
		if ( $desktop_css !== '' && isset( $this->breakpoints['desktop'] ) ) {
			$desktop_css = '@media (min-width: ' . $this->breakpoints['desktop'] . ') {' . $eol . $desktop_css . $eol . '}' . $eol;
		}

		return $parse_css . $tablet_css . $desktop_css;
	}

	/**
	 * Convert spacing control value to css output
	 *
	 * @param mixed $value
	 * @param string $selector
	 *
	 * @return array
	 */
	public function dimensions( $value, $selector = 'margin' ) {
		if ( ! isset( $value['desktop'] ) ) {
			$value = [ null => $value ];
		}

		$spacingCss = [];

		foreach ( $value as $device => $data ) {
			$top    = $data['top'] ?? '0';
			$right  = $data['right'] ?? '0';
			$bottom = $data['bottom'] ?? '0';
			$left   = $data['left'] ?? '0';

			$spacingCss[ $selector ] = $this->getResponsiveValue(
				"$top $right $bottom $left", $device, $spacingCss[ $selector ] ?? null
			);
		}

		return $spacingCss;
	}

	/**
	 * Convert background control value to css output
	 *
	 * @param array $background
	 *
	 * @return array
	 */
	public function background( $background ) {
		if ( ! isset( $background['desktop'] ) ) {
			$background = [ null => $background ];
		}

		$backgroundCss = [];

		foreach ( $background as $device => $data ) {

			if ( $data['type'] === 'color' ) {
				if ( ( $data['color'] ?? '' ) === 'inherit' ) {
					continue;
				}

				// solid color type
				$backgroundCss['background-color'] = $this->getResponsiveValue(
					$data['color'] ?? '', $device,
					$backgroundCss['background-color'] ?? null
				);
				// override background image
				$backgroundCss['background-image'] = $this->getResponsiveValue(
					'none', $device,
					$backgroundCss['background-image'] ?? null
				);
			} else if ( $data['type'] === 'gradient' ) {
				// gradient type
				$backgroundCss['background-image'] = $this->getResponsiveValue(
					$data['gradient'] ?? '', $device,
					$backgroundCss['background-image'] ?? null
				);
			} else if ( $data['type'] === 'image' ) {
				// background image
				$image = $data['image'] ?? [];

				if ( isset( $image['color'] ) ) {
					$backgroundCss['background-color'] = $this->getResponsiveValue(
						$image['color'], $device, $backgroundCss['background-color'] ?? null
					);
				}
				if ( isset( $image['size'] ) ) {
					$backgroundCss['background-size'] = $this->getResponsiveValue(
						$image['size'], $device, $backgroundCss['background-size'] ?? null
					);
				}
				if ( isset( $image['repeat'] ) ) {
					$backgroundCss['background-repeat'] = $this->getResponsiveValue(
						$image['repeat'], $device, $backgroundCss['background-repeat'] ?? null
					);
				}
				if ( isset( $image['attachment'] ) ) {
					$backgroundCss['background-attachment'] = $this->getResponsiveValue(
						$image['attachment'], $device, $backgroundCss['background-attachment'] ?? null
					);
				}

				if ( isset( $image['source'] ) && isset( $image['source']['url'] ) ) {

					$backgroundCss['background-image'] = $this->getResponsiveValue(
						'url(' . $image['source']['url'] . ')', $device,
						$backgroundCss['background-image'] ?? null
					);

					if ( isset( $image['source']['x'] ) && isset( $image['source']['y'] ) ) {
						$x = $image['source']['x'] * 100;
						$y = $image['source']['y'] * 100;

						$backgroundCss['background-position'] = $this->getResponsiveValue(
							"$x% $y%", $device, $backgroundCss['background-position'] ?? null
						);
					}
				}
			}
		}

		return $backgroundCss;
	}

	/**
	 * Convert border control to css output
	 *
	 * @param $selector
	 * @param array $border
	 *
	 * @return array
	 */
	public function border( $border, $selector = 'border' ) {
		if ( ! isset( $border['desktop'] ) ) {
			$border = [ null => $border ];
		}

		$borderCss = [];

		foreach ( $border as $device => $data ) {
			$value = 'none';
			$style = $data['style'] ?? 'none';
			$width = ( $data['width'] ?? '0' ) . 'px';
			$color = $data['color'] ?? '';
			$hover = $data['hover'] ?? '';

			if ( $style !== 'none' ) {
				$value = "$width $style $color";
			}

			$borderCss[ $selector ] = $this->getResponsiveValue(
				$value, $device, $borderCss[ $selector ] ?? null
			);

			$borderCss['--lotta-border-initial-color'] = $this->getResponsiveValue(
				$color, $device, $borderCss['--lotta-border-initial-color'] ?? null
			);

			$borderCss['--lotta-border-hover-color'] = $this->getResponsiveValue(
				$hover, $device, $borderCss['--lotta-border-hover-color'] ?? null
			);
		}

		return $borderCss;
	}

	/**
	 * Convert shadow control value to css output
	 *
	 * @param mixed $shadow
	 * @param string $selector
	 *
	 * @return array
	 */
	public function shadow( $shadow, $selector = 'box-shadow' ) {
		if ( ! isset( $shadow['desktop'] ) ) {
			$shadow = [ null => $shadow ];
		}

		$shadowCss = [];

		foreach ( $shadow as $device => $data ) {
			$value  = 'none';
			$enable = ( $data['enable'] ?? '' ) === 'yes';
			$h      = $data['horizontal'] ?? '0';
			$v      = $data['vertical'] ?? '0';
			$blur   = $data['blur'] ?? '0';
			$spread = $data['spread'] ?? '0';
			$color  = $data['color'] ?? '';

			if ( $enable ) {
				$value = "$color $h $v $blur $spread";
			}

			$shadowCss[ $selector ] = $this->getResponsiveValue(
				$value, $device, $shadowCss[ $selector ] ?? null
			);
		}

		return $shadowCss;
	}

	/**
	 * Convert typography control value to css output
	 *
	 * @param array $typography
	 *
	 * @return array
	 */
	public function typography( $typography ) {
		$system = Fonts::system_fonts();
		$google = Fonts::google_fonts();

		$family  = $typography['family'] ?? 'inherit';
		$variant = $typography['variant'] ?? '400';

		if ( isset( $system[ $family ] ) ) {
			if ( isset( $system[ $family ]['s'] ) && ! empty( $system[ $family ]['s'] ) ) {
				$family = $system[ $family ]['s'];
			}
		}

		if ( isset( $google[ $family ] ) ) {
			$family   = $google[ $family ]['f'] ?? $family;
			$variants = $google[ $family ]['v'] ?? [];
			$variant  = in_array( $variant, $variants ) ? $variant : ( $variants[0] ?? '400' );
		}

		return [
			'font-family'     => $family,
			'font-weight'     => $variant,
			'font-size'       => $typography['fontSize'] ?? '',
			'line-height'     => $typography['lineHeight'] ?? '',
			'letter-spacing'  => $typography['letterSpacing'] ?? '',
			'text-transform'  => $typography['textTransform'] ?? '',
			'text-decoration' => $typography['textDecoration'] ?? '',
		];
	}

	/**
	 * Convert color control value to css output
	 *
	 * @param $colors
	 * @param $maps
	 * @param array $css
	 *
	 * @return array
	 */
	public function colors( $colors, $maps, $css = [] ) {
		foreach ( $maps as $color => $key ) {
			if ( isset( $colors[ $color ] ) ) {
				$css[ $key ] = $colors[ $color ];
			}
		}

		return $css;
	}

	/**
	 * Get value for responsive
	 *
	 * @param $value
	 * @param null $device
	 * @param null $previous
	 *
	 * @return array|mixed|null
	 */
	protected function getResponsiveValue( $value, $device = null, $previous = null ) {

		if ( ! $device ) {
			return $value;
		}

		$value = [
			$device => $value
		];

		return is_array( $previous ) ? array_merge( $previous, $value ) : $value;
	}
}