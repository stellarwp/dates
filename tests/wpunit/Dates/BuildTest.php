<?php
namespace StellarWP\Dates\Dates;

use StellarWP\Dates\Dates;
use StellarWP\Dates\Tests\DatesTestCase;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;

/**
 * @backupStaticAttributes
 */
class BuildTest extends DatesTestCase {
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

	public function build_date_object_empty_data_set() {
		return [
			'zero'           => [ 0 ],
			'empty_string'   => [ '' ],
			'false'          => [ false ],
			'foo_bar_string' => [ 'foo bar' ],
		];
	}

	/**
	 * @dataProvider build_date_object_empty_data_set
	 */
	public function test_building_date_object_for_empty_will_return_today_date( $input ) {
		$expected = ( new \DateTime( 'now' ) )->format( 'Y-m-d' );
		// Do not test to the second as run times might yield false negatives.
		$this->assertEquals( $expected, Dates::get( $input )->format( Dates::DBDATEFORMAT ) );
		$this->assertEquals( $expected, Dates::mutable( $input )->format( Dates::DBDATEFORMAT ) );
		$this->assertEquals( $expected, Dates::immutable( $input )->format( Dates::DBDATEFORMAT ) );
	}

	public function build_date_object_data_set() {
		yield '2019-12-01 08:00:00 string' => [ '2019-12-01 08:00:00', '2019-12-01 08:00:00' ];
		yield '2019-12-01 08:00:00 DateTime' => [ new DateTime( '2019-12-01 08:00:00' ), '2019-12-01 08:00:00' ];
		yield '2019-12-01 08:00:00 DateTimeImmutable' => [
			new DateTimeImmutable( '2019-12-01 08:00:00' ),
			'2019-12-01 08:00:00'
		];
		yield '2019-12-01 08:00:00 timestamp' => [
			( new DateTime( '2019-12-01 08:00:00' ) )->getTimestamp(),
			'2019-12-01 08:00:00'
		];

		$timezone_str = 'Europe/Paris';
		$timezone_obj = new DateTimeZone($timezone_str);

		yield '2019-12-01 08:00:00 string w/ timezone' => [
			'2019-12-01 08:00:00',
			'2019-12-01 08:00:00',
			$timezone_str,
		];
		yield '2019-12-01 08:00:00 DateTime w/timezone' => [
			new DateTime( '2019-12-01 08:00:00', $timezone_obj ),
			'2019-12-01 08:00:00',
			$timezone_str,
		];
		yield '2019-12-01 08:00:00 DateTimeImmutable w/ timezone' => [
			new DateTimeImmutable( '2019-12-01 08:00:00', $timezone_obj ),
			'2019-12-01 08:00:00',
			$timezone_str,
		];
		yield '2019-12-01 08:00:00 timestamp w/ timezone' => [
			( new DateTime( '2019-12-01 08:00:00', $timezone_obj ) )->getTimestamp(),
			'2019-12-01 07:00:00',
			$timezone_str,
		];

		yield '2019-12-01 08:00:00 string w/ timezone obj' => [
			'2019-12-01 08:00:00',
			'2019-12-01 08:00:00',
			$timezone_obj,
		];
		yield '2019-12-01 08:00:00 DateTime w/timezone' => [
			new DateTime( '2019-12-01 08:00:00', $timezone_obj ),
			'2019-12-01 08:00:00',
			$timezone_obj,
		];
		yield '2019-12-01 08:00:00 DateTimeImmutable w/ timezone obj' => [
			new DateTimeImmutable( '2019-12-01 08:00:00', $timezone_obj ),
			'2019-12-01 08:00:00',
			$timezone_obj,
		];
		yield '2019-12-01 08:00:00 timestamp w/ timezone obj' => [
			( new DateTimeImmutable( '2019-12-01 08:00:00', $timezone_obj ) )->getTimestamp(),
			'2019-12-01 07:00:00',
			$timezone_obj,
		];
	}

	/**
	 * @dataProvider build_date_object_data_set
	 */
	public function test_build_date_object( $input, $expected, $timezone = null ) {
		$this->assertEquals(
			$expected,
			Dates::get( $input, $timezone )->format( Dates::DBDATETIMEFORMAT )
		);
		$this->assertEquals(
			$expected,
			Dates::mutable( $input, $timezone )->format( Dates::DBDATETIMEFORMAT )
		);
		$this->assertEquals(
			$expected,
			Dates::immutable( $input, $timezone )->format( Dates::DBDATETIMEFORMAT )
		);
	}
}
