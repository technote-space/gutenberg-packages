<?php
/**
 * @author Technote
 * @copyright Technote All Rights Reserved
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU General Public License, version 2
 * @link https://technote.space
 */

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Trait Testable
 */
trait Testable {

	/**
	 * @return array
	 */
	protected function target_args() {
		return [];
	}

	/**
	 * @param array $args
	 */
	private function setup_args( array $args ) {
		foreach ( $this->target_args() as $param ) {
			$this->$param = null;
			if ( isset( $args[ $param ] ) ) {
				$this->$param = $args[ $param ];
			}
		}
	}

	/**
	 * @param $target
	 * @param $name
	 * @param $value
	 *
	 * @throws ReflectionException
	 */
	private function set_property( $target, $name, $value ) {
		$reflection = new ReflectionClass( $target );
		$property   = $reflection->getProperty( $name );
		$property->setAccessible( true );
		$property->setValue( $target, $value );
		$property->setAccessible( false );
	}

}
