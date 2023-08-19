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

	/**
	 * @test
	 */
	public function it_should_get_the_start_and_end_of_the_week() {
		$days = Dates::get_week_start_end( '2023-01-18' );
		$this->assertEquals( '2023-01-16', $days[0]->format( Dates::DBDATEFORMAT ) );
		$this->assertEquals( '2023-01-22', $days[1]->format( Dates::DBDATEFORMAT ) );

		$days = Dates::get_week_start_end( '2023-01-18', 0 );
		$this->assertEquals( '2023-01-15', $days[0]->format( Dates::DBDATEFORMAT ) );
		$this->assertEquals( '2023-01-21', $days[1]->format( Dates::DBDATEFORMAT ) );

		$days = Dates::get_week_start_end( '2023-01-18', 3 );
		$this->assertEquals( '2023-01-18', $days[0]->format( Dates::DBDATEFORMAT ) );
		$this->assertEquals( '2023-01-24', $days[1]->format( Dates::DBDATEFORMAT ) );
	}

	/**
	 * @test
	 */
	public function it_should_end_after_7_days() {
		$this->assertEquals( 6, Dates::week_ends_on( 0 ) );
		$this->assertEquals( 0, Dates::week_ends_on( 1 ) );
		$this->assertEquals( 1, Dates::week_ends_on( 2 ) );
		$this->assertEquals( 2, Dates::week_ends_on( 3 ) );
		$this->assertEquals( 3, Dates::week_ends_on( 4 ) );
		$this->assertEquals( 4, Dates::week_ends_on( 5 ) );
		$this->assertEquals( 5, Dates::week_ends_on( 6 ) );
	}

	public function get_range_overlaps() {
		yield 'range 2 starts before range 1 and ends during' => [
			'range_1_start' => '2023-01-01',
			'range_1_end'   => '2023-01-31',
			'range_2_start' => '2022-12-15',
			'range_2_end'   => '2023-01-15',
			'expected'      => true,
		];

		yield 'range 2 starts during range 1 and ends after' => [
			'range_1_start' => '2023-01-01',
			'range_1_end'   => '2023-01-31',
			'range_2_start' => '2023-01-15',
			'range_2_end'   => '2023-02-15',
			'expected'      => true,
		];

		yield 'range 2 starts before range 1 and ends after' => [
			'range_1_start' => '2023-01-01',
			'range_1_end'   => '2023-01-31',
			'range_2_start' => '2022-12-15',
			'range_2_end'   => '2023-02-15',
			'expected'      => true,
		];

		yield 'range 2 starts in range 1 and ends during' => [
			'range_1_start' => '2023-01-01',
			'range_1_end'   => '2023-01-31',
			'range_2_start' => '2023-01-15',
			'range_2_end'   => '2023-01-25',
			'expected'      => true,
		];

		yield 'range 2 starts at the end of range 1 and ends after' => [
			'range_1_start' => '2023-01-01',
			'range_1_end'   => '2023-01-31',
			'range_2_start' => '2023-01-31',
			'range_2_end'   => '2023-02-15',
			'expected'      => false,
		];

		yield 'range 2 starts and ends after range 1' => [
			'range_1_start' => '2023-01-01',
			'range_1_end'   => '2023-01-31',
			'range_2_start' => '2023-02-01',
			'range_2_end'   => '2023-02-15',
			'expected'      => false,
		];

		yield 'range 2 starts and ends before range 1' => [
			'range_1_start' => '2023-01-01',
			'range_1_end'   => '2023-01-31',
			'range_2_start' => '2022-12-01',
			'range_2_end'   => '2022-12-31',
			'expected'      => false,
		];

		yield 'range 2 starts before range 1 and ends on the start' => [
			'range_1_start' => '2023-01-01',
			'range_1_end'   => '2023-01-31',
			'range_2_start' => '2022-12-01',
			'range_2_end'   => '2022-01-01',
			'expected'      => false,
		];
	}

	/**
	 * @dataProvider get_range_overlaps
	 * @test
	 */
	public function it_should_identify_range_overlaps( $range_1_start, $range_1_end, $range_2_start, $range_2_end, $expected ) {
		$this->assertEquals( $expected, Dates::range_overlaps( $range_1_start, $range_1_end, $range_2_start, $range_2_end ) );
	}
}
