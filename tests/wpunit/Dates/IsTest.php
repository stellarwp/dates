<?php

namespace StellarWP\Dates\Dates;

use StellarWP\Dates\Dates;
use StellarWP\Dates\Tests\DatesTestCase;

/**
 * @backupStaticAttributes
 */
class IsTest extends DatesTestCase {
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

	public function get_date_between_dates() {
		yield 'date in middle of dates' => [
			'start'    => '2020-01-01',
			'end'      => '2021-01-01',
			'date'     => '2020-06-01',
			'expected' => true,
		];

		yield 'date the same as start date' => [
			'start'    => '2020-01-01',
			'end'      => '2021-01-01',
			'date'     => '2020-01-01',
			'expected' => true,
		];

		yield 'date the same as start date, but time is earlier' => [
			'start'    => '2020-01-01 10:00:00',
			'end'      => '2021-01-01',
			'date'     => '2020-01-01',
			'expected' => false,
		];

		yield 'date the same as end date' => [
			'start'    => '2020-01-01',
			'end'      => '2021-01-01',
			'date'     => '2020-01-01',
			'expected' => true,
		];

		yield 'date the same as end date, but time is later' => [
			'start'    => '2020-01-01',
			'end'      => '2021-01-01',
			'date'     => '2021-01-01 10:00:00',
			'expected' => false,
		];
	}

	/**
	 * @dataProvider get_date_between_dates
	 * @test
	 */
	public function it_identifies_when_date_is_between_dates( $start, $end, $date, $expected ) {
		$this->assertEquals( $expected, Dates::is_now( $start, $end, $date ) );
	}

	public function get_timestamps() {
		yield 'numeric timestamp' => [
			'timestamp' => time(),
			'expected'  => true,
		];

		yield 'timestamp as a string' => [
			'timestamp' => (string) time(),
			'expected'  => true,
		];

		yield 'timestamp with a decimal is not valid' => [
			'timestamp' => time() . '.123',
			'expected'  => false,
		];

		yield 'beans should not be a timestamp' => [
			'timestamp' => 'beans',
			'expected'  => false,
		];
	}

	/**
	 * @dataProvider get_timestamps
	 * @test
	 */
	public function it_identifies_timestamps( $timestamp, $expected ) {
		$this->assertEquals( $expected, Dates::is_timestamp( $timestamp ) );
	}

	public function get_dates() {
		yield 'january 1st is valid' => [
			'date' => '2020-01-01',
			'expected'  => true,
		];

		yield 'timestamp is valid' => [
			'timestamp' => time(),
			'expected'  => true,
		];

		yield 'february 30 is valid because it rolls forward into march' => [
			'date' => '2020-02-30',
			'expected'  => true,
		];

		yield '2 digit year is valid' => [
			'date' => '10-10-10',
			'expected'  => true,
		];

		yield '1 digit month is valid' => [
			'date' => '10-1-10',
			'expected'  => true,
		];

		yield '1 digit day is valid' => [
			'date' => '10-1-1',
			'expected'  => true,
		];

		yield 'beans is not valid' => [
			'date' => 'beans',
			'expected'  => false,
		];
	}

	/**
	 * @dataProvider get_dates
	 * @test
	 */
	public function it_identifies_valid_dates( $date, $expected ) {
		$this->assertEquals( $expected, Dates::is_valid_date( $date ) );
	}

	public function get_weekday_data() {
		yield 'monday is weekday' => [
			'date' => '2023-08-14',
			'expected'  => true,
		];

		yield 'tuesday is weekday' => [
			'date' => '2023-08-15',
			'expected'  => true,
		];

		yield 'wednesday is weekday' => [
			'date' => '2023-08-16',
			'expected'  => true,
		];

		yield 'thursday is weekday' => [
			'date' => '2023-08-17',
			'expected'  => true,
		];

		yield 'friday is weekday' => [
			'date' => '2023-08-18',
			'expected'  => true,
		];

		yield 'saturday is not weekday' => [
			'date' => '2023-08-19',
			'expected'  => false,
		];

		yield 'sunday is not weekday' => [
			'date' => '2023-08-20',
			'expected'  => false,
		];
	}

	/**
	 * @dataProvider get_weekday_data
	 * @test
	 */
	public function it_identifies_weekdays( $date, $expected ) {
		$this->assertEquals( $expected, Dates::is_weekday( $date ) );
	}

	public function get_weekend_data() {
		yield 'monday is not weekend' => [
			'date' => '2023-08-14',
			'expected'  => false,
		];

		yield 'tuesday is not weekend' => [
			'date' => '2023-08-15',
			'expected'  => false,
		];

		yield 'wednesday is not weekend' => [
			'date' => '2023-08-16',
			'expected'  => false,
		];

		yield 'thursday is not weekend' => [
			'date' => '2023-08-17',
			'expected'  => false,
		];

		yield 'friday is not weekend' => [
			'date' => '2023-08-18',
			'expected'  => false,
		];

		yield 'saturday is weekend' => [
			'date' => '2023-08-19',
			'expected'  => true,
		];

		yield 'sunday is weekend' => [
			'date' => '2023-08-20',
			'expected'  => true,
		];
	}

	/**
	 * @dataProvider get_weekend_data
	 * @test
	 */
	public function it_identifies_weekends( $date, $expected ) {
		$this->assertEquals( $expected, Dates::is_weekend( $date ) );
	}
}
