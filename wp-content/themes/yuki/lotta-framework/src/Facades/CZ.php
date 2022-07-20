<?php

namespace LottaFramework\Facades;

/**
 * @method static register( $args, bool $sub = false )
 * @method static get( string $id ) mixed
 * @method static checked( string $id ) bool
 * @method static display( string $id, $visible = 'block' ) mixed
 * @method static layers( string $id ) array
 * @method static repeater( string $id ) array
 * @method static getSettingArgs( $id ) mixed
 * @method static addSection( \WP_Customize_Manager $WP_customize, $id, $args = [], $controls = [] )
 * @method static addControl( \WP_Customize_Manager $wp_customize, $args, bool $has_control = true )
 * @method static getSubControls( $args )
 * @method static changeObject( \WP_Customize_Manager $wp_customize, string $type, string $id, $property, $value )
 */
class CZ extends Facade {
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() {
		return \LottaFramework\Customizer\Customizer::class;
	}
}