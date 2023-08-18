<?php
namespace StellarWP\Dates\Dates;

use StellarWP\Dates\Dates;
use StellarWP\Dates\Tests\DatesTestCase;

/**
 * @backupStaticAttributes
 */
class FormatTest extends DatesTestCase {
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

	/**
	 * unescape_date_format will return input if not a string
	 */
	public function test_unescape_date_format_will_return_input_if_not_a_string() {
		$bad_input = array( 23 );
		$this->assertEquals( $bad_input, Dates::unescape_date_format( $bad_input ) );
	}

	public function date_formats_not_to_escape() {
		return [
			[ 'tribe', 'tribe' ],
			[ 'j \d\e F', 'j \d\e F' ],
			[ 'F, \e\l j', 'F, \e\l j' ],
			[ '\hH', '\hH' ],
			[ 'i\m, s\s', 'i\m, s\s' ],
			[ '\T\Z: T ', '\T\Z: T' ],
		];
	}

	/**
	 * unescape_date_format will return same string when nothing to escape
	 *
	 * @dataProvider date_formats_not_to_escape
	 */
	public function test_unescape_date_format_will_return_same_string_when_nothing_to_escape( $in ) {
		$out = Dates::unescape_date_format( $in );
		$this->assertEquals( $in, $out );
	}

	public function date_formats_to_escape() {
		return [
			[ 'j \\d\\e F', 'j \d\e F' ],
			[ 'F, \\e\\l j', 'F, \e\l j' ],
			[ '\\hH', '\hH' ],
			[ 'i\\m, s\\s', 'i\m, s\s' ],
			[ '\\T\\Z: T', '\T\Z: T' ],
			[ 'j \d\\e F', 'j \d\e F' ],
			[ 'F, \e\\l j', 'F, \e\l j' ],
			[ 'i\m, s\\s', 'i\m, s\s' ],
			[ '\T\\Z: T', '\T\Z: T' ],
		];
	}

	/**
	 * unescape_date_format will return escaped date format
	 *
	 * @dataProvider date_formats_to_escape
	 */
	public function test_unescape_date_format_will_return_escaped_date_format( $in, $expected_out ) {
		$out = Dates::unescape_date_format( $in );
		$this->assertEquals( $expected_out, $out );
	}

	public function reformat_inputs() {
		return [
			[ 'tomorrow 9am', 'U' ],
			[ 'tomorrow 9am', 'Y-m-d' ],
			[ 'tomorrow 9am', 'H:i:s' ],
			[ 'tomorrow 9am', 'Y-m-d H:i:s' ],
		];
	}

	/**
	 * Test reformat
	 *
	 * @test
	 * @dataProvider reformat_inputs
	 */
	public function test_reformat( $input, $format ) {
		$date = Dates::get( $input );

		$this->assertEquals( $date->format( $format ), Dates::reformat( $input, $format ) );
		$this->assertEquals( $date->format( 'U' ), Dates::reformat( $input, 'U' ) );
		$this->assertEquals( $date->format( $format ), Dates::reformat( $date->format( 'U' ), $format ) );
		$this->assertEquals( $date->format( 'U' ), Dates::reformat( $date->format( 'U' ), 'U' ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_the_date_parts() {
		$date = '2010-01-02 13:14:15';

		$this->assertEquals( substr( $date, 0, 10 ), Dates::date_only( $date ) );
		$this->assertEquals( 1, Dates::hour_only( $date ) );
		$this->assertEquals( 13, Dates::hour_only( $date, true ) );
		$this->assertEquals( 14, Dates::minutes_only( $date ) );
		$this->assertEquals( 15, Dates::seconds_only( $date ) );
		$this->assertEquals( substr( $date, -8 ), Dates::time_only( $date ) );
		$this->assertEquals( 'PM', Dates::meridian_only( $date ) );
	}
}
