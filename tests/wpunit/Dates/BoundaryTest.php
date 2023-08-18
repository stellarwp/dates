<?php

namespace StellarWP\Dates\Dates;

use StellarWP\Dates\Dates;
use StellarWP\Dates\Tests\DatesTestCase;

/**
 * @backupStaticAttributes
 */
class BoundaryTest extends DatesTestCase {
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

	public function get_first_day_by_month() {
		yield 'january is sunday' => [
			'2023-01-01 00:00:00',
			0,
		];

		yield 'february is wednesday' => [
			'2023-02-01 00:00:00',
			3,
		];

		yield 'march is wednesday' => [
			'2023-03-01 00:00:00',
			3,
		];

		yield 'april is saturday' => [
			'2023-04-01 00:00:00',
			6,
		];

		yield 'may is monday' => [
			'2023-05-01 00:00:00',
			1,
		];

		yield 'june is thursday' => [
			'2023-06-01 00:00:00',
			4,
		];

		yield 'july is saturday' => [
			'2023-07-01 00:00:00',
			6,
		];

		yield 'august is tuesday' => [
			'2023-08-01 00:00:00',
			2,
		];

		yield 'september is friday' => [
			'2023-09-01 00:00:00',
			5,
		];

		yield 'october is sunday' => [
			'2023-10-01 00:00:00',
			0,
		];

		yield 'november is wednesday' => [
			'2023-11-01 00:00:00',
			3,
		];

		yield 'december is friday' => [
			'2023-12-01 00:00:00',
			5,
		];
	}

	/**
	 * @dataProvider get_first_day_by_month
	 * @test
	 */
	public function it_should_get_first_day_in_month( $date, $expected ) {
		$this->assertEquals( $expected, Dates::first_day_in_month( $date )->format( 'w' ) );
	}

	public function get_last_day_by_month() {
		yield 'january is tuesday' => [
			'2023-01-01 00:00:00',
			2,
		];

		yield 'february is tuesday' => [
			'2023-02-01 00:00:00',
			2,
		];

		yield 'march is friday' => [
			'2023-03-01 00:00:00',
			5,
		];

		yield 'april is sunday' => [
			'2023-04-01 00:00:00',
			0,
		];

		yield 'may is wednesday' => [
			'2023-05-01 00:00:00',
			3,
		];

		yield 'june is friday' => [
			'2023-06-01 00:00:00',
			5,
		];

		yield 'july is monday' => [
			'2023-07-01 00:00:00',
			1,
		];

		yield 'august is thursday' => [
			'2023-08-01 00:00:00',
			4,
		];

		yield 'september is saturday' => [
			'2023-09-01 00:00:00',
			6,
		];

		yield 'october is tuesday' => [
			'2023-10-01 00:00:00',
			2,
		];

		yield 'november is thursday' => [
			'2023-11-01 00:00:00',
			4,
		];

		yield 'december is sunday' => [
			'2023-12-01 00:00:00',
			0,
		];
	}

	/**
	 * @dataProvider get_last_day_by_month
	 * @test
	 */
	public function it_should_get_last_day_in_month( $date, $expected ) {
		$this->assertEquals( $expected, Dates::last_day_in_month( $date )->format( 'w' ) );
	}

	public function get_first_day_of_the_week_in_month() {
		yield 'january - sunday' => [
			'date' => '2023-01-01',
			'day'  => 7,
			'expected' => '2023-01-01',
		];

		yield 'january - monday' => [
			'date' => '2023-01-01',
			'day'  => 1,
			'expected' => '2023-01-02',
		];

		yield 'january - tuesday' => [
			'date' => '2023-01-01',
			'day'  => 2,
			'expected' => '2023-01-03',
		];

		yield 'january - wednesday' => [
			'date' => '2023-01-01',
			'day' => 3,
			'expected' => '2023-01-04',
		];

		yield 'january - thursday' => [
			'date' => '2023-01-01',
			'day' => 4,
			'expected' => '2023-01-05',
		];

		yield 'january - friday' => [
			'date' => '2023-01-01',
			'day' => 5,
			'expected' => '2023-01-06',
		];

		yield 'january - saturday' => [
			'date' => '2023-01-01',
			'day' => 6,
			'expected' => '2023-01-07',
		];
	}

	/**
	 * @dataProvider get_first_day_of_the_week_in_month
	 * @test
	 */
	public function it_should_get_first_day_of_the_week_in_month( $date, $day, $expected ) {
		$this->assertEquals( $expected, Dates::get_first_day_of_week_in_month( $date, $day )->format( Dates::DBDATEFORMAT ) );
	}

	public function get_last_day_of_the_week_in_month() {
		yield 'january - sunday' => [
			'date' => '2023-01-01',
			'day'  => 7,
			'expected' => '2023-01-29',
		];

		yield 'january - monday' => [
			'date' => '2023-01-01',
			'day'  => 1,
			'expected' => '2023-01-30',
		];

		yield 'january - tuesday' => [
			'date' => '2023-01-01',
			'day'  => 2,
			'expected' => '2023-01-31',
		];

		yield 'january - wednesday' => [
			'date' => '2023-01-01',
			'day' => 3,
			'expected' => '2023-01-25',
		];

		yield 'january - thursday' => [
			'date' => '2023-01-01',
			'day' => 4,
			'expected' => '2023-01-26',
		];

		yield 'january - friday' => [
			'date' => '2023-01-01',
			'day' => 5,
			'expected' => '2023-01-27',
		];

		yield 'january - saturday' => [
			'date' => '2023-01-01',
			'day' => 6,
			'expected' => '2023-01-28',
		];
	}

	/**
	 * @dataProvider get_last_day_of_the_week_in_month
	 * @test
	 */
	public function it_should_get_last_day_of_the_week_in_month( $date, $day, $expected ) {
		$this->assertEquals( $expected, Dates::get_last_day_of_week_in_month( $date, $day )->format( Dates::DBDATEFORMAT ) );
	}
}
