<?php
/**
 * @author Technote
 * @copyright Technote All Rights Reserved
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU General Public License, version 2
 * @link https://technote.space
 */

namespace Technote\Tests;

use PHPUnit\Framework\TestCase;
use Technote\Collection as CollectionClass;
use /** @noinspection PhpUndefinedClassInspection */
	WP_UnitTestCase;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// @codeCoverageIgnoreEnd

/**
 * @noinspection PhpUndefinedClassInspection
 * Collection test case.
 *
 * @mixin TestCase
 */
class Collection extends WP_UnitTestCase {

	private function get_collection( $items ) {
		return new CollectionClass( $items );
	}

	public function test_to_array() {
		$this->assertEquals( [], $this->get_collection( [] )->to_array() );
		$this->assertEquals( [ 1, 2, 3 ], $this->get_collection( [ 1, 2, 3 ] )->to_array() );
	}

	public function test_pluck() {
		$this->assertEquals( [ 1, 2, 3 ],
			$this->get_collection( [
				[
					'a' => 1,
					'b' => 10,
				],
				[
					'a' => 2,
					'b' => 20,
				],
				[
					'a' => 3,
					'b' => 30,
				],
			] )->pluck( 'a' )
		);
	}

	public function test_combine() {
		$this->assertEquals(
			[
				'a' => 1,
				'b' => 2,
				'c' => 3,
			],
			$this->get_collection( [
				[
					'x' => 'a',
					'y' => 1,
				],
				[
					'x' => 'b',
					'y' => 2,
				],
				[
					'x' => 'c',
					'y' => 3,
				],
			] )->combine( 'x', 'y' )
		);
	}

	public function test_get() {
		$this->assertEquals( 1,
			$this->get_collection(
				[
					'a' => 1,
					'b' => 2,
					'c' => 3,
				]
			)->get( 'a' )
		);
		$this->assertEquals( 3,
			$this->get_collection(
				[
					'a' => 1,
					'b' => 2,
					'c' => 3,
				]
			)->get( 'c' )
		);
	}

	public function test_exists() {
		$this->assertEquals( true,
			$this->get_collection(
				[
					'a' => 1,
					'b' => 2,
					'c' => 3,
				]
			)->exists( 'a' )
		);
		$this->assertEquals( false,
			$this->get_collection(
				[
					'a' => 1,
					'b' => 2,
					'c' => 3,
				]
			)->exists( 'd' )
		);
	}

	public function test_filter() {
		$this->assertEquals(
			[ 'c' => 3 ],
			$this->get_collection(
				[
					'a' => 1,
					'b' => 2,
					'c' => 3,
				]
			)->filter( function ( $value, $key ) {
				return 1 === $value % 2 && 'a' !== $key;
			} )->to_array()
		);
	}

	public function test_map() {
		$this->assertEquals(
			[
				'a' => 'a:2',
				'b' => 'b:4',
				'c' => 'c:6',
			],
			$this->get_collection(
				[
					'a' => 1,
					'b' => 2,
					'c' => 3,
				]
			)->map( function ( $value, $key ) {
				return $key . ':' . $value * 2;
			} )->to_array()
		);
	}

	public function test_unique() {
		$this->assertEquals(
			[
				0 => 1,
				1 => 2,
				3 => 3,
			],
			$this->get_collection( [ 1, 2, 2, 3, 1, 3, 3, 2 ] )->unique()->to_array()
		);
		$this->assertEquals( [ 1, 2, 3 ],
			$this->get_collection( [ 1, 2, 2, 3, 1, 3, 3, 2 ] )->unique()->values()->to_array()
		);
		$this->assertEquals(
			[
				'a' => 1,
				'c' => 2,
			],
			$this->get_collection(
				[
					'a' => 1,
					'b' => 1,
					'c' => 2,
				]
			)->unique()->to_array()
		);
	}

	public function test_merge() {
		$this->assertEquals( [ 1, 2, 3 ],
			$this->get_collection( [ 1, 2, 3 ] )->merge( [] )->to_array()
		);

		$this->assertEquals(
			[ 1, 2, 3, 4, 1, 3, 5, 1 ],
			$this->get_collection(
				[ 1, 2, 3, 4 ]
			)->merge( [ 1, 3, 5, 1 ] )->to_array()
		);

		$this->assertEquals(
			[
				'a' => 'test1',
				'b' => 'test4',
				'c' => 'test3',
				'd' => 'test5',
			],
			$this->get_collection(
				[
					'a' => 'test1',
					'b' => 'test2',
					'c' => 'test3',
				]
			)->merge(
				[
					'b' => 'test4',
					'd' => 'test5',
				]
			)->to_array()
		);
	}

}
