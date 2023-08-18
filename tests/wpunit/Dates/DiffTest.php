<?php
namespace StellarWP\Dates\Dates;

use StellarWP\Dates\Dates;
use StellarWP\Dates\Tests\DatesTestCase;

/**
 * @backupStaticAttributes
 */
class DiffTest extends DatesTestCase {
	/**
	 * @var string
	 */
	protected static $tz_backup;

	protected $backupGlobals = false;

	public static function setUpBeforeClass() {
		self::$tz_backup = date_default_timezone_get();

		return parent::setUpBeforeClass();
	}

	public static function tearDownAfterClass() {
		date_default_timezone_set( self::$tz_backup );
		update_option( 'timezone_string', self::$tz_backup );

		return parent::tearDownAfterClass();
	}

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here

		// Default timezone to UTC at beginning of each test
		date_default_timezone_set( 'UTC' );
		update_option( 'timezone_string', 'UTC' );
	}

	public function tearDown() {
		// your tear down methods here
		update_option( 'timezone_string', 'UTC' );

		// then
		parent::tearDown();
		Dates::$cache = [];
	}

	public function date_diffs() {
		yield 'same day' => [
			'2020-01-01 00:00:00',
			'2020-01-01 00:00:00',
			0,
		];

		yield 'same day, different time' => [
			'2020-01-01 00:00:00',
			'2020-01-01 23:00:00',
			0,
		];

		yield 'different day, same time' => [
			'2020-01-01 00:00:00',
			'2020-01-07 00:00:00',
			6,
		];

		yield 'different day, different time' => [
			'2020-01-01 00:00:00',
			'2020-01-07 12:00:00',
			6,
		];
	}

	/**
	 * @dataProvider date_diffs
	 * @test
	 */
	public function it_should_diff_dates( $date_1, $date_2, $expected ) {
		$this->assertEquals( $expected, Dates::diff( $date_1, $date_2 ) );
	}


	public function time_diffs() {
		yield 'same day' => [
			'2020-01-01 00:00:00',
			'2020-01-01 00:00:00',
			0,
		];

		yield 'same day, different time' => [
			'2020-01-01 00:00:00',
			'2020-01-01 23:00:00',
			23 * 60 * 60,
		];

		yield 'different day, same time' => [
			'2020-01-01 00:00:00',
			'2020-01-07 00:00:00',
			6 * 24 * 60 * 60,
		];

		yield 'different day, different time' => [
			'2020-01-01 00:00:00',
			'2020-01-07 12:00:00',
			6 * 24 * 60 * 60 + 12 * 60 * 60,
		];
	}

	/**
	 * @dataProvider time_diffs
	 * @test
	 */
	public function it_should_diff_dates_with_seconds( $date_1, $date_2, $expected ) {
		$this->assertEquals( $expected, Dates::time_between( $date_1, $date_2 ) );
	}
}
