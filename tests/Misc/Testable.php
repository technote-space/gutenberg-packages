<?php
/**
 * @author Technote
 * @copyright Technote All Rights Reserved
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU General Public License, version 2
 * @link https://technote.space
 */

namespace Technote\Tests\Misc;

use ReflectionClass;
use ReflectionException;

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
	 * @param array $args
	 */
	public function reset_args( array $args ) {
		$this->setup_args( $args );
	}

	/**
	 * @param $name
	 * @param $value
	 * @param $target
	 *
	 * @throws ReflectionException
	 */
	public function set_property( $name, $value, $target = null ) {
		if ( ! isset( $target ) ) {
			$target = $this;
		}
		$reflection = new ReflectionClass( $target );
		$property   = $reflection->getProperty( $name );
		$property->setAccessible( true );
		$property->setValue( $target, $value );
		$property->setAccessible( false );
	}

	/**
	 * @param string $key
	 */
	private function delete_transient( $key ) {
		delete_transient( 'Technote/GutenbergPackages/' . $key );
	}

}
