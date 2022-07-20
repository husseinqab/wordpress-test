<?php

namespace LottaFramework\Customizer\Controls;

use LottaFramework\Customizer\Control;
use LottaFramework\Customizer\Sanitizes;

class ColorPicker extends Control {

	/**
	 * {@inheritDoc}
	 */
	public function __construct( $id ) {
		parent::__construct( $id );

		$this->enableAlpha();
	}

	public function getType(): string {
		return 'lotta-color-picker';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getSanitize() {
		return [ Sanitizes::class, 'rgba_color_collect' ];
	}

	public function disableAlpha() {
		return $this->setOption( 'alpha', false );
	}

	public function enableAlpha() {
		return $this->setOption( 'alpha', true );
	}

	public function setSwatches( $swatches ) {
		return $this->setOption( 'swatches', $swatches );
	}

	public function addColor( $id, $title, $default = '' ) {
		$defaultParam = $this->params['default'] ?? [];
		$colorsOption = $this->options['colors'] ?? [];

		$defaultParam[ $id ] = $default;
		$colorsOption[]      = [
			'title' => $title,
			'id'    => $id,
		];

		return $this->setOption( 'colors', $colorsOption )
		            ->setDefaultValue( $defaultParam );
	}
}