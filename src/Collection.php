<?php
/**
 * @author Technote
 * @copyright Technote All Rights Reserved
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU General Public License, version 2
 * @link https://technote.space
 */

namespace Technote;

use Closure;
use Traversable;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * Class Collection
 * @package Technote
 */
class Collection {

	/**
	 * @var array $items
	 */
	private $items;

	/**
	 * Collection constructor.
	 *
	 * @param array|Traversable $items
	 */
	public function __construct( $items ) {
		$this->items = $items instanceof Traversable ? iterator_to_array( $items ) : $items;
	}

	/**
	 * @return array
	 */
	public function to_array() {
		return $this->items;
	}

	/**
	 * @param string $key
	 *
	 * @return array
	 */
	public function pluck( $key ) {
		return array_map( function ( $item ) use ( $key ) {
			return $item[ $key ];
		}, $this->items );
	}

	/**
	 * @param string $key
	 * @param string $value
	 *
	 * @return array
	 */
	public function combine( $key, $value ) {
		return array_combine( $this->pluck( $key ), $this->pluck( $value ) );
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function get( $key, $default = null ) {
		return array_key_exists( $key, $this->items ) ? $this->items[ $key ] : $default;
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function exists( $key ) {
		return array_key_exists( $key, $this->items );
	}

	/**
	 * @param Closure $callback
	 *
	 * @return $this
	 */
	public function filter( $callback ) {
		foreach ( $this->items as $key => $value ) {
			if ( ! call_user_func_array( $callback, [ $value, $key ] ) ) {
				unset( $this->items[ $key ] );
			}
		}

		return $this;
	}

	/**
	 * @param Closure $callback
	 *
	 * @return $this
	 */
	public function map( $callback ) {
		foreach ( $this->items as $key => $value ) {
			$this->items[ $key ] = call_user_func_array( $callback, [ $value, $key ] );
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	public function unique() {
		$this->items = array_unique( $this->items );

		return $this;
	}

	/**
	 * @return $this
	 */
	public function values() {
		$this->items = array_values( $this->items );

		return $this;
	}

	/**
	 * @param array $items
	 *
	 * @return $this
	 */
	public function merge( array $items ) {
		if ( ! empty( $items ) ) {
			$this->items = array_merge( $this->items, $items );
		}

		return $this;
	}

}
