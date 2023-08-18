<?php

namespace StellarWP\Dates;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Exception;
use RuntimeException;

class Dates {
	// Default formats, they are overridden by WP options or by arguments to date methods
	const DATEONLYFORMAT        = 'F j, Y';
	const TIMEFORMAT            = 'g:i A';
	const HOURFORMAT            = 'g';
	const MINUTEFORMAT          = 'i';
	const MERIDIANFORMAT        = 'A';
	const DBDATEFORMAT          = 'Y-m-d';
	const DBDATETIMEFORMAT      = 'Y-m-d H:i:s';
	const DBTZDATETIMEFORMAT    = 'Y-m-d H:i:s O';
	const DBTIMEFORMAT          = 'H:i:s';
	const DBYEARMONTHTIMEFORMAT = 'Y-m';

	protected static $localized_months_full = [];
	protected static $localized_months_short = [];
	protected static $localized_weekdays = [];
	protected static $localized_months = [];

	public static $cache = [];

	/**
	 * Builds a date object from a given datetime and timezone.
	 *
	 * Defaults to immutable, but can be set to return a mutable DateTime object.
	 *
	 * @since 1.0.5
	 *
	 * @param string|DateTimeInterface|int $datetime      A `strtotime` parsable string, a DateTime object or
	 *                                                    a timestamp; defaults to `now`.
	 * @param string|DateTimeZone|null     $timezone      A timezone string, UTC offset or DateTimeZone object;
	 *                                                    defaults to the site timezone; this parameter is ignored
	 *                                                    if the `$datetime` parameter is a DatTime object.
	 * @param bool                         $with_fallback Whether to return a DateTime object even when the date data is
	 *                                                    invalid or not; defaults to `true`.
	 * @param bool                         $immutable     Whether to return a DateTimeImmutable object or a DateTime object;
	 *
	 * @return DateTime|DateTimeImmutable|false A DateTime object built using the specified date, time and timezone; if `$with_fallback`
	 *                        is set to `false` then `false` will be returned if a DateTime object could not be built.
	 */
	public static function build( $datetime = 'now', $timezone = null, bool $with_fallback = true, bool $immutable = true ) {
		if ( $immutable ) {
			return static::immutable( $datetime, $timezone, $with_fallback );
		}
		return self::mutable( $datetime, $timezone, $with_fallback );
	}

	/**
	 * Alias of the mutable() method. Builds a date object from a given datetime and timezone.
	 *
	 * @since 1.0.0
	 *
	 * @param string|DateTimeInterface|int $datetime      A `strtotime` parsable string, a DateTime object or
	 *                                                    a timestamp; defaults to `now`.
	 * @param string|DateTimeZone|null     $timezone      A timezone string, UTC offset or DateTimeZone object;
	 *                                                    defaults to the site timezone; this parameter is ignored
	 *                                                    if the `$datetime` parameter is a DatTime object.
	 * @param bool                         $with_fallback Whether to return a DateTime object even when the date data is
	 *                                                    invalid or not; defaults to `true`.
	 *
	 * @return DateTime|false A DateTime object built using the specified date, time and timezone; if `$with_fallback`
	 *                        is set to `false` then `false` will be returned if a DateTime object could not be built.
	 */
	public static function build_date_object( $datetime = 'now', $timezone = null, bool $with_fallback = true ) {
		return self::mutable( $datetime, $timezone, $with_fallback );
	}

	/**
	 * Builds arrays of localized full and short months.
	 *
	 * @since 1.0.0
	 */
	private static function build_localized_months() {
		global $wp_locale;

		for ( $i = 1; $i <= 12; $i++ ) {
			$month_number                                     = str_pad( (string) $i, 2, '0', STR_PAD_LEFT );
			$month                                            = $wp_locale->get_month( $month_number );
			self::$localized_months['full'][ $month_number ]  = $month;
			self::$localized_months['short'][ $month_number ] = $wp_locale->get_month_abbrev( $month );
		}
	}

	/**
	 * Builds arrays of localized full, short and initialized weekdays.
	 */
	private static function build_localized_weekdays() {
		global $wp_locale;

		for ( $i = 0; $i <= 6; $i++ ) {
			$day                                       = $wp_locale->get_weekday( $i );
			self::$localized_weekdays['full'][ $i ]    = $day;
			self::$localized_weekdays['short'][ $i ]   = $wp_locale->get_weekday_abbrev( $day );
			self::$localized_weekdays['initial'][ $i ] = $wp_locale->get_weekday_initial( $day );
		}
	}

	/**
	 * A convenience function used to cast errors to exceptions.
	 *
	 * Use in `set_error_handler` calls:
	 *
	 *      try{
	 *          set_error_handler( [ __CLASS__, 'catch_and_throw' ] );
	 *          // ...do something that could generate an error...
	 *          restore_error_handler();
	 *      } catch ( RuntimeException $e ) {
	 *          // Handle the exception.
	 *      }
	 *
	 * @see   set_error_handler()
	 * @see   restore_error_handler()
	 * @since 1.1.0
	 *
	 * @throws RuntimeException The message will be the error message, the code will be the error code.
	 */
	public static function catch_and_throw( $errno, $errstr ) {
		throw new RuntimeException( $errstr, $errno );
	}

	/**
	 * Resets the cache.
	 */
	public static function clear_cache() {
		return static::$cache = [];
	}

	/**
	 * Alias for diff(). The number of days between two arbitrary dates.
	 *
	 * @param string|int|DateTime|DateTimeImmutable $date1 The first date.
	 * @param string|int|DateTime|DateTimeImmutable $date2 The second date.
	 *
	 * @return int The number of days between two dates.
	 */
	public static function date_diff( $date1, $date2 ) {
		return static::diff( $date1, $date2 );
	}

	/**
	 * Returns the date only.
	 *
	 * @since 1.0.0
	 *
	 * @param string|int|DateTime|DateTimeImmutable $date        The date (timestamp or string).
	 * @param bool                                  $isTimestamp Is $date in timestamp format?
	 * @param string|null                           $format      The format used
	 *
	 * @return string The date only in DB format.
	 */
	public static function date_only( $date, $isTimestamp = false, $format = null ) {
		$date = static::get( $date );

		if ( is_null( $format ) ) {
			$format = self::DBDATEFORMAT;
		}

		return $date->format( $format );
	}

	/**
	 * As PHP 5.2 doesn't have a good version of `date_parse_from_format`, this is how we deal with
	 * possible weird datepicker formats not working
	 *
	 * @param string $format The weird format you are using
	 * @param string $date   The date string to parse
	 *
	 * @return string|bool         A DB formated Date, includes time if possible
	 */
	public static function datetime_from_format( $format, $date ) {
		// Reverse engineer the relevant date formats
		$keys = [
			// Year with 4 Digits
			'Y' => [ 'year', '\d{4}' ],

			// Year with 2 Digits
			'y' => [ 'year', '\d{2}' ],

			// Month with leading 0
			'm' => [ 'month', '\d{2}' ],

			// Month without the leading 0
			'n' => [ 'month', '\d{1,2}' ],

			// Month ABBR 3 letters
			'M' => [ 'month', '[A-Z][a-z]{2}' ],

			// Month Name
			'F' => [ 'month', '[A-Z][a-z]{2,8}' ],

			// Day with leading 0
			'd' => [ 'day', '\d{2}' ],

			// Day without leading 0
			'j' => [ 'day', '\d{1,2}' ],

			// Day ABBR 3 Letters
			'D' => [ 'day', '[A-Z][a-z]{2}' ],

			// Day Name
			'l' => [ 'day', '[A-Z][a-z]{5,8}' ],

			// Hour 12h formatted, with leading 0
			'h' => [ 'hour', '\d{2}' ],

			// Hour 24h formatted, with leading 0
			'H' => [ 'hour', '\d{2}' ],

			// Hour 12h formatted, without leading 0
			'g' => [ 'hour', '\d{1,2}' ],

			// Hour 24h formatted, without leading 0
			'G' => [ 'hour', '\d{1,2}' ],

			// Minutes with leading 0
			'i' => [ 'minute', '\d{2}' ],

			// Seconds with leading 0
			's' => [ 'second', '\d{2}' ],
		];

		$date_regex = "/{$keys['Y'][1]}-{$keys['m'][1]}-{$keys['d'][1]}( {$keys['H'][1]}:{$keys['i'][1]}:{$keys['s'][1]})?$/";

		// if the date is already in Y-m-d or Y-m-d H:i:s, just return it
		if ( preg_match( $date_regex, $date ) ) {
			return $date;
		}

		// Convert format string to regex
		$regex = '';
		$chars = str_split( $format );
		foreach ( $chars as $n => $char ) {
			$last_char    = isset( $chars[ $n - 1 ] ) ? $chars[ $n - 1 ] : '';
			$skip_current = '\\' == $last_char;
			if ( ! $skip_current && isset( $keys[ $char ] ) ) {
				$regex .= '(?P<' . $keys[ $char ][0] . '>' . $keys[ $char ][1] . ')';
			} elseif ( '\\' == $char ) {
				$regex .= $char;
			} else {
				$regex .= preg_quote( $char );
			}
		}

		$dt = [];

		// Now try to match it
		if ( preg_match( '#^' . $regex . '$#', $date, $dt ) ) {
			// Remove unwanted Indexes
			foreach ( $dt as $k => $v ) {
				if ( is_int( $k ) ) {
					unset( $dt[ $k ] );
				}
			}

			// We need at least Month + Day + Year to work with
			if ( ! checkdate( (int) $dt['month'], (int) $dt['day'], (int) $dt['year'] ) ) {
				return false;
			}
		} else {
			return false;
		}

		$dt['month'] = str_pad( $dt['month'], 2, '0', STR_PAD_LEFT );
		$dt['day']   = str_pad( $dt['day'], 2, '0', STR_PAD_LEFT );

		$formatted = '{year}-{month}-{day}' . ( isset( $dt['hour'], $dt['minute'], $dt['second'] ) ? ' {hour}:{minute}:{second}' : '' );
		foreach ( $dt as $key => $value ) {
			$formatted = str_replace( '{' . $key . '}', $value, $formatted );
		}

		return $formatted;
	}

	/**
	 * The number of days between two arbitrary dates.
	 *
	 * @param string|int|DateTime|DateTimeImmutable $date1 The first date.
	 * @param string|int|DateTime|DateTimeImmutable $date2 The second date.
	 *
	 * @return int The number of days between two dates.
	 */
	public static function diff( $date1, $date2 ) {
		// Get number of days between by finding seconds between and dividing by # of seconds in a day
		$days = self::time_between( $date1, $date2 ) / ( 60 * 60 * 24 );

		return (int) floor( $days );
	}

	/**
	 * Returns the weekday of the 1st day of the month in
	 * "w" format (ie, Sunday is 0 and Saturday is 6) or
	 * false if this cannot be established.
	 *
	 * @param string|int|DateTime|DateTimeImmutable $month
	 *
	 * @return DateTime|DateTimeImmutable|bool
	 */
	public static function first_day_in_month( $month ) {
		try {
			$date  = static::get( $month );
			return static::get( $date->format( 'Y-m-01' ) );
		} catch ( Exception $e ) {
			return false;
		}
	}

	/**
	 * Builds a date object from a given datetime and timezone.
	 *
	 * Defaults to immutable, but can be set to return a mutable DateTime object.
	 *
	 * @since 1.0.5
	 *
	 * @param string|DateTimeInterface|int $datetime      A `strtotime` parsable string, a DateTime object or
	 *                                                    a timestamp; defaults to `now`.
	 * @param string|DateTimeZone|null     $timezone      A timezone string, UTC offset or DateTimeZone object;
	 *                                                    defaults to the site timezone; this parameter is ignored
	 *                                                    if the `$datetime` parameter is a DatTime object.
	 * @param bool                         $with_fallback Whether to return a DateTime object even when the date data is
	 *                                                    invalid or not; defaults to `true`.
	 * @param bool                         $immutable     Whether to return a DateTimeImmutable object or a DateTime object;
	 *
	 * @return DateTime|DateTimeImmutable|false A DateTime object built using the specified date, time and timezone; if `$with_fallback`
	 *                        is set to `false` then `false` will be returned if a DateTime object could not be built.
	 */
	public static function get( $datetime = 'now', $timezone = null, bool $with_fallback = true, bool $immutable = true ) {
		return static::build( $datetime, $timezone, $with_fallback, $immutable );
	}

	/**
	 * Gets a value from the cache.
	 *
	 * @param string $key
	 * @param mixed  $default
	 *
	 * @return mixed
	 */
	public static function get_cache( $key, $default = null ) {
		return isset( static::$cache[ $key ] ) ? static::$cache[ $key ] : $default;
	}

	/**
	 * Gets the first day of the week in a month (ie the first Tuesday).
	 *
	 * @param string|int|DateTime|DateTimeImmutable $curdate     A timestamp.
	 * @param int                                   $day_of_week The index of the day of the week.
	 *
	 * @return DateTime|DateTimeImmutable The date that fits the qualifications.
	 */
	public static function get_first_day_of_week_in_month( $curdate, $day_of_week ) {
		$curdate_object = static::get( $curdate );
		$nextdate       = static::get( $curdate_object->format( 'Y-m-01' ) );
		$failover_date  = $nextdate;

		if ( $day_of_week < 1 ) {
			$day_of_week = 1;
		} elseif ( $day_of_week > 7 ) {
			$day_of_week = 7;
		}

		for ( $i = 0; $i < 7; $i++ ) {
			if ( (int) $nextdate->format( 'N' ) === $day_of_week ) {
				return $nextdate;
			}

			$nextdate = $nextdate->modify( '+ 1 day' );
		}

		// For some reason, we couldn't find a date. Fail over to the first of the month.
		return $failover_date;
	}

	/**
	 * Returns the last day of the month given a php date.
	 *
	 * @param string|int|DateTime|DateTimeImmutable $timestamp The timestamp.
	 *
	 * @return DateTime|DateTimeImmutable The last day of the month.
	 */
	public static function get_last_day_of_month( $timestamp ) {
		$date_object = static::get( $timestamp );
		$curmonth    = (int) $date_object->format( 'n' );
		$curYear     = (int) $date_object->format( 'Y' );
		$nextmonth   = static::get( "{$curYear}-{$curmonth}-01" );
		$lastDay     = $nextmonth->modify( '-1 day' );

		return $lastDay;
	}

	/**
	 * Gets the last day of the week in a month (ie the last Tuesday).  Passing in -1 gives you the last day in the month.
	 *
	 * @param string|int|DateTime|DateTimeImmutable $curdate     A timestamp.
	 * @param int                                   $day_of_week The index of the day of the week.
	 *
	 * @return DateTime|DateTimeImmutable The timestamp of the date that fits the qualifications.
	 */
	public static function get_last_day_of_week_in_month( $curdate, int $day_of_week ) {
		$curdate            = static::get( $curdate );
		$last_day_of_month  = static::get_last_day_of_month( $curdate );
		$last_date_of_month = $last_day_of_month->format( 'j' );
		$curdate_string     = $curdate->format( "Y-m-{$last_date_of_month} H:i:s" );
		$nextdate           = static::get( $curdate_string );
		$failover_date      = $last_day_of_month;

		if ( $day_of_week < 1 ) {
			$day_of_week = 1;
		} elseif ( $day_of_week > 7 ) {
			$day_of_week = 7;
		}

		for ( $i = 0; $i < 7; $i++ ) {
			if ( (int) $nextdate->format( 'N' ) === $day_of_week ) {
				return $nextdate;
			}

			$nextdate = $nextdate->modify( '-1 day' );
		}

		return $failover_date;
	}

	/**
	 * Returns an array of localized full month names.
	 *
	 * @return array
	 */
	public static function get_localized_months_full(): array {
		if ( empty( self::$localized_months ) ) {
			self::build_localized_months();
		}

		if ( empty( self::$localized_months_full ) ) {
			self::$localized_months_full = [
				'January'   => self::$localized_months['full']['01'],
				'February'  => self::$localized_months['full']['02'],
				'March'     => self::$localized_months['full']['03'],
				'April'     => self::$localized_months['full']['04'],
				'May'       => self::$localized_months['full']['05'],
				'June'      => self::$localized_months['full']['06'],
				'July'      => self::$localized_months['full']['07'],
				'August'    => self::$localized_months['full']['08'],
				'September' => self::$localized_months['full']['09'],
				'October'   => self::$localized_months['full']['10'],
				'November'  => self::$localized_months['full']['11'],
				'December'  => self::$localized_months['full']['12'],
			];
		}

		return self::$localized_months_full;
	}

	/**
	 * Returns an array of localized short month names.
	 *
	 * @return array
	 */
	public static function get_localized_months_short(): array {
		if ( empty( self::$localized_months ) ) {
			self::build_localized_months();
		}

		if ( empty( self::$localized_months_short ) ) {
			self::$localized_months_short = [
				'Jan' => self::$localized_months['short']['01'],
				'Feb' => self::$localized_months['short']['02'],
				'Mar' => self::$localized_months['short']['03'],
				'Apr' => self::$localized_months['short']['04'],
				'May' => self::$localized_months['short']['05'],
				'Jun' => self::$localized_months['short']['06'],
				'Jul' => self::$localized_months['short']['07'],
				'Aug' => self::$localized_months['short']['08'],
				'Sep' => self::$localized_months['short']['09'],
				'Oct' => self::$localized_months['short']['10'],
				'Nov' => self::$localized_months['short']['11'],
				'Dec' => self::$localized_months['short']['12'],
			];
		}

		return self::$localized_months_short;
	}

	/**
	 * Returns an array of localized full week day names.
	 *
	 * @return array
	 */
	public static function get_localized_weekdays_full(): array {
		if ( empty( self::$localized_weekdays ) ) {
			self::build_localized_weekdays();
		}

		return self::$localized_weekdays['full'];
	}

	/**
	 * Returns an array of localized week day initials.
	 *
	 * @return array
	 */
	public static function get_localized_weekdays_initial(): array {
		if ( empty( self::$localized_weekdays ) ) {
			self::build_localized_weekdays();
		}

		return self::$localized_weekdays['initial'];
	}

	/**
	 * Returns an array of localized short week day names.
	 *
	 * @return array
	 */
	public static function get_localized_weekdays_short(): array {
		if ( empty( self::$localized_weekdays ) ) {
			self::build_localized_weekdays();
		}

		return self::$localized_weekdays['short'];
	}

	/**
	 * Accepts a numeric offset (such as "4" or "-6" as stored in the gmt_offset
	 * option) and converts it to a strtotime() style modifier that can be used
	 * to adjust a DateTime object, etc.
	 *
	 * @param $offset
	 *
	 * @return string
	 */
	public static function get_modifier_from_offset( $offset ): string {
		$modifier = '';
		$offset   = (float) $offset;

		// Separate out hours, minutes, polarity
		$hours    = (int) $offset;
		$minutes  = (int) ( ( $offset - $hours ) * 60 );
		$polarity = ( $offset >= 0 ) ? '+' : '-';

		// Correct hours and minutes to positive values
		if ( $hours < 0 ) {
			$hours *= -1;
		}
		if ( $minutes < 0 ) {
			$minutes *= -1;
		}

		// Form the modifier string
		$modifier = "$polarity $hours hours ";
		if ( $minutes > 0 ) {
			$modifier .= "$minutes minutes";
		}

		return $modifier;
	}

	/**
	 * Returns the DateTime object representing the start of the week for a date.
	 *
	 * @since 1.0.0
	 *
	 * @param string|int|\DateTime $date          The date string, timestamp or object.
	 * @param int|null             $start_of_week The number representing the start of week day as handled by
	 *                                            WordPress: `0` (for Sunday) through `6` (for Saturday).
	 *
	 * @return array An array of objects representing the week start and end days, or `false` if the
	 *                        supplied date is invalid. The timezone of the returned object is set to the site one.
	 *                        The week start has its time set to `00:00:00`, the week end will have its time set
	 *                        `23:59:59`.
	 * @throws Exception
	 *
	 */
	public static function get_week_start_end( $date, $start_of_week = null ): array {
		static $cache_var_name = __FUNCTION__;

		$cache_week_start_end = static::get_cache( $cache_var_name, [] );

		$date_obj = static::mutable( $date );
		$date_obj->setTime( 0, 0, 0 );

		$date_string = $date_obj->format( static::DBDATEFORMAT );

		// `0` (for Sunday) through `6` (for Saturday), the way WP handles the `start_of_week` option.
		$week_start_day = null !== $start_of_week
			? (int) $start_of_week
			: (int) get_option( 'start_of_week', 0 );

		$memory_cache_key = "{$date_string}:{$week_start_day}";

		if ( isset( $cache_week_start_end[ $memory_cache_key ] ) ) {
			return $cache_week_start_end[ $memory_cache_key ];
		}

		$cache_key = md5(
			__METHOD__ . serialize( [ $date_obj->format( static::DBDATEFORMAT ), $week_start_day ] )
		);

		$cached = static::get_cache( $cache_key, false );

		if ( false !== $cached ) {
			return $cached;
		}

		// `0` (for Sunday) through `6` (for Saturday), the way WP handles the `start_of_week` option.
		$date_day = (int) $date_obj->format( 'w' );

		$week_offset = 0;
		if ( 0 === $date_day && 0 !== $week_start_day ) {
			$week_offset = 0;
		} elseif ( $date_day < $week_start_day ) {
			// If the current date of the week is before the start of the week, move back a week.
			$week_offset = -1;
		} elseif ( 0 === $date_day ) {
			// When start of the week is on a sunday we add a week.
			$week_offset = 1;
		}

		$week_start = clone $date_obj;

		/*
		 * From the PHP docs, the `W` format stands for:
		 * - ISO-8601 week number of year, weeks starting on Monday
		 */
		$week_start->setISODate(
			(int) $week_start->format( 'o' ),
			(int) $week_start->format( 'W' ) + $week_offset,
			$week_start_day
		);

		$week_end = clone $week_start;
		// Add 6 days, then move at the end of the day.
		$week_end->add( new DateInterval( 'P6D' ) );
		$week_end->setTime( 23, 59, 59 );

		$week_start = static::immutable( $week_start );
		$week_end   = static::immutable( $week_end );

		static::set_cache( $cache_key, [ $week_start, $week_end ] );
		$cache_week_start_end[ $memory_cache_key ] = [ $week_start, $week_end ];

		static::set_cache( $cache_var_name, $cache_week_start_end );

		return [ $week_start, $week_end ];
	}

	/**
	 * Gets the timestamp of a day in week, month and year context.
	 *
	 * Kudos to [icedwater StackOverflow user](http://stackoverflow.com/users/1091386/icedwater) in
	 * [his answer](http://stackoverflow.com/questions/924246/get-the-first-or-last-friday-in-a-month).
	 *
	 * Usage examples:
	 * "The second Wednesday of March 2015" - `get_day_timestamp( 3, 2, 3, 2015, 1)`
	 * "The last Friday of December 2015" - `get_day_timestamp( 5, 1, 12, 2015, -1)`
	 * "The first Monday of April 2016 - `get_day_timestamp( 1, 1, 4, 2016, 1)`
	 * "The penultimate Thursday of January 2012" - `get_day_timestamp( 4, 2, 1, 2012, -1)`
	 *
	 * @param int          $day_of_week    The day representing the number in the week, Monday is `1`, Tuesday is `2`, Sunday is `7`
	 * @param int          $week_in_month  The week number in the month; first week is `1`, second week is `2`; when direction is reverse
	 *                                     then `1` is last week of the month, `2` is penultimate week of the month and so on.
	 * @param int          $month          The month number in the year, January is `1`
	 * @param int          $year           The year number, e.g. "2015"
	 * @param int          $week_direction Either `1` or `-1`; the direction for the search referring to the week, defaults to `1`
	 *                                     to specify weeks in natural order so:
	 *                                     $week_direction `1` and $week_in_month `1` means "first week of the month"
	 *                                     $week_direction `1` and $week_in_month `3` means "third week of the month"
	 *                                     $week_direction `-1` and $week_in_month `1` means "last week of the month"
	 *                                     $week_direction `-1` and $week_in_month `2` means "penultimmate week of the month"
	 *
	 * @return int|bool The day timestamp
	 */
	public static function get_weekday_timestamp( int $day_of_week, int $week_in_month, int $month, int $year, int $week_direction = 1 ) {
		if ( ! in_array( $week_direction, [ -1, 1 ] ) ) {
			return false;
		}

		if ( $week_direction > 0 ) {
			$startday = 1;
		} else {
			$startday = static::get( "{$year}-{$month}-01" )->format( 't' );
		}

		$start   = static::get( "{$year}-{$month}-{$startday}" );
		$weekday = $start->format( 'N' );

		if ( $week_direction * $day_of_week >= $week_direction * $weekday ) {
			$offset = -$week_direction * 7;
		} else {
			$offset = 0;
		}

		$offset       += $week_direction * ( $week_in_month * 7 ) + ( $day_of_week - $weekday );
		$offset_start = $startday + $offset;

		return static::get( "{$year}-{$month}-{$offset_start}" )->getTimestamp();
	}

	/**
	 * Determines if a cache value exists.
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public static function has_cache( $key ) {
		return isset( static::$cache[ $key ] );
	}

	/**
	 * Returns the hour only.
	 *
	 * @param string|int|DateTime|DateTimeImmutable $date The date.
	 * @param bool                                  $use_24_hour Whether to use 24 hour format.
	 *
	 * @return string The hour only.
	 */
	public static function hour_only( $date, $use_24_hour = false ): string {
		return static::get( $date )->format( $use_24_hour ? 'H' : static::HOURFORMAT );
	}

	/**
	 * Builds the immutable version of a date from a string, integer (timestamp) or \DateTime object.
	 *
	 * It's the immutable version of the `Dates::mutable` method.
	 *
	 * @since 1.0.0
	 *
	 * @param string|DateTimeInterface|int $datetime      A `strtotime` parsable string, a DateTime object or
	 *                                                    a timestamp; defaults to `now`.
	 * @param string|DateTimeZone|null     $timezone      A timezone string, UTC offset or DateTimeZone object;
	 *                                                    defaults to the site timezone; this parameter is ignored
	 *                                                    if the `$datetime` parameter is a DatTime object.
	 * @param bool                         $with_fallback Whether to return a DateTime object even when the date data is
	 *                                                    invalid or not; defaults to `true`.
	 *
	 * @return DateTimeImmutable|false A DateTime object built using the specified date, time and timezone; if
	 *                                 `$with_fallback` is set to `false` then `false` will be returned if a
	 *                                 DateTime object could not be built.
	 */
	static function immutable( $datetime = 'now', $timezone = null, bool $with_fallback = true ) {
		if ( $datetime instanceof DateTimeImmutable ) {
			return $datetime;
		}

		if ( $datetime instanceof DateTime ) {
			return Date_I18n_Immutable::createFromMutable( $datetime );
		}

		$mutable = static::mutable( $datetime, $timezone, $with_fallback );

		if ( false === $mutable ) {
			return false;
		}

		$cache_key = md5( ( __METHOD__ . $mutable->getTimezone()->getName() . $mutable->getTimestamp() ) );

		$cached = static::get_cache( $cache_key, false );

		if ( false !== $cached ) {
			return $cached;
		}

		$immutable = Date_I18n_Immutable::createFromMutable( $mutable );

		static::set_cache( $cache_key, $immutable );

		return $immutable;
	}

	/**
	 * Builds and returns a `DateInterval` object from the interval specification.
	 *
	 * For performance purposes the use of `DateInterval` specifications is preferred, so `P1D` is better than
	 * `1 day`.
	 *
	 * @since 1.0.0
	 *
	 * @return DateInterval The built date interval object.
	 */
	public static function interval( $interval_spec ): DateInterval {
		try {
			$interval = new \DateInterval( $interval_spec );
		} catch ( \Exception $e ) {
			$interval = DateInterval::createFromDateString( $interval_spec );
		}

		return $interval;
	}

	/**
	 * Determine if "now" is between two dates.
	 *
	 * @since 1.0.0
	 *
	 * @param string|DateTimeInterface|int $start_date A `strtotime` parsable string, a DateTime object or a timestamp.
	 * @param string|DateTimeInterface|int $end_date   A `strtotime` parsable string, a DateTime object or a timestamp.
	 * @param string|DateTimeInterface|int $now        A `strtotime` parsable string, a DateTime object or a timestamp. Defaults to 'now'.
	 *
	 * @return boolean Whether the current datetime (or passed "now") is between the passed start and end dates.
	 */
	public static function is_now( $start_date, $end_date, $now = 'now' ): bool {
		$now        = self::mutable( $now );
		$start_date = self::mutable( $start_date );
		$end_date   = self::mutable( $end_date );

		// If the dates are identical, bail early.
		if ( $start_date === $end_date ) {
			return false;
		}

		// Handle dates passed out of chronological order.
		[ $start_date, $end_date ] = self::sort( [ $start_date, $end_date ] );

		// If span starts after now, return false.
		if ( $start_date > $now ) {
			return false;
		}

		// If span ends on or before now, return false.
		if ( $end_date <= $now ) {
			return false;
		}

		return true;
	}

	/**
	 * check if a given string is a timestamp
	 *
	 * @param $timestamp
	 *
	 * @return bool
	 */
	public static function is_timestamp( $timestamp ): bool {
		$date = new \DateTimeImmutable();
		if ( ! is_numeric( $timestamp ) ) {
			return false;
		}

		// Purposefully using loose comparison here to match non-numeric strings.
		if ( (int) $timestamp != $timestamp ) {
			return false;
		}

		$timestamp = (int) $timestamp;

		if ( $timestamp <= 0 ) {
			return false;
		}

		// Convert to a date and back again to ensure that the timestamp remains the same.
		$converted_timestamp = (int) strtotime( date( 'Y-m-d H:i:s', $timestamp ) );

		if ( $converted_timestamp != $timestamp ) {
			return false;
		}

		return true;
	}

	/**
	 * Validates a date string to make sure it can be used to build DateTime objects.
	 *
	 * @since 1.0.0
	 *
	 * @param string $date The date string that should validated.
	 *
	 * @return bool Whether the date string can be used to build DateTime objects, and is thus parsable by functions
	 *              like `strtotime`, or not.
	 */
	public static function is_valid_date( string $date ): bool {
		static $cache_var_name = __FUNCTION__;

		$cache_date_check = static::get_cache( $cache_var_name, [] );

		if ( isset( $cache_date_check[ $date ] ) ) {
			return $cache_date_check[ $date ];
		}

		$date_object = self::mutable( $date, null, false );

		$cache_date_check[ $date ] = $date_object instanceof DateTimeInterface;

		static::set_cache( $cache_var_name, $cache_date_check );

		return $cache_date_check[ $date ];
	}

	/**
	 * Returns true if the timestamp is a weekday.
	 *
	 * @param string|int|DateTime|DateTimeImmutable $curdate A timestamp or date.
	 *
	 * @return bool If the timestamp is a weekday.
	 */
	public static function is_weekday( $curdate ): bool {
		return in_array( static::get( $curdate )->format( 'N' ), [ 1, 2, 3, 4, 5 ] );
	}

	/**
	 * Returns true if the timestamp is a weekend.
	 *
	 * @param string|int|DateTime|DateTimeImmutable $curdate A timestamp or date.
	 *
	 * @return bool If the timestamp is a weekend.
	 */
	public static function is_weekend( $curdate ) {
		return in_array( static::get( $curdate )->format( 'N' ), [ 6, 7 ] );
	}

	/**
	 * Returns the weekday of the last day of the month in
	 * "w" format (ie, Sunday is 0 and Saturday is 6) or
	 * false if this cannot be established.
	 *
	 * @param string|int|DateTime|DateTimeImmutable $month
	 *
	 * @return DateTime|DateTimeImmutable|bool
	 */
	public static function last_day_in_month( $month ) {
		try {
			$date  = static::get( $month );
			$day_1 = static::get( $date->format( 'Y-m-t' ) );
			return $day_1;
		} catch ( Exception $e ) {
			return false;
		}
	}

	/**
	 * Returns the meridian (am or pm) only.
	 *
	 * @param string|int|DateTime|DateTimeImmutable $date The date.
	 *
	 * @return string The meridian only in DB format.
	 */
	public static function meridian_only( $date ): string {
		return static::get( $date )->format( self::MERIDIANFORMAT );
	}

	/**
	 * Returns the minute only.
	 *
	 * @param string|int|DateTime|DateTimeImmutable $date The date.
	 *
	 * @return string The minute only.
	 */
	public static function minutes_only( $date ) {
		return static::get( $date )->format( self::MINUTEFORMAT );
	}

	/**
	 * Builds a date object from a given datetime and timezone.
	 *
	 * @since 1.0.0
	 *
	 * @param string|DateTimeInterface|int $datetime      A `strtotime` parsable string, a DateTime object or
	 *                                                    a timestamp; defaults to `now`.
	 * @param string|DateTimeZone|null     $timezone      A timezone string, UTC offset or DateTimeZone object;
	 *                                                    defaults to the site timezone; this parameter is ignored
	 *                                                    if the `$datetime` parameter is a DatTime object.
	 * @param bool                         $with_fallback Whether to return a DateTime object even when the date data is
	 *                                                    invalid or not; defaults to `true`.
	 *
	 * @return DateTime|false A DateTime object built using the specified date, time and timezone; if `$with_fallback`
	 *                        is set to `false` then `false` will be returned if a DateTime object could not be built.
	 */
	public static function mutable( $datetime = 'now', $timezone = null, bool $with_fallback = true ) {
		if ( $datetime instanceof DateTime ) {
			return clone $datetime;
		}

		if ( $datetime instanceof DateTimeImmutable ) {
			// Return the mutable version of the date.
			return Date_I18n::createFromImmutable( $datetime );
		}

		$timezone_object = null;
		$datetime        = empty( $datetime ) ? 'now' : $datetime;

		try {
			// PHP 5.2 will not throw an exception but will generate an error.
			$utc             = new DateTimeZone( 'UTC' );
			$timezone_object = Timezones::build_timezone_object( $timezone );

			if ( self::is_timestamp( $datetime ) ) {
				$timestamp_timezone = $timezone ? $timezone_object : $utc;

				return new Date_I18n( '@' . $datetime, $timestamp_timezone );
			}

			set_error_handler( [ __CLASS__, 'catch_and_throw' ] );
			$date = new Date_I18n( $datetime, $timezone_object );
			restore_error_handler();
		} catch ( Exception $e ) {
			// If we encounter an error, we need to restore after catching.
			restore_error_handler();

			if ( $timezone_object === null ) {
				$timezone_object = Timezones::build_timezone_object( $timezone );
			}

			return $with_fallback
				? new Date_I18n( 'now', $timezone_object )
				: false;
		}

		return $date;
	}

	/**
	 * From http://php.net/manual/en/function.date.php
	 *
	 * @param int $number A number.
	 *
	 * @return string The ordinal for that number.
	 */
	public static function number_to_ordinal( $number ) {
		if (
			strlen( $number ) > 1
			&& substr( $number, -2, 1 ) == '1'
		) {
			$output = "{$number}th";
		} else {
			$last_number = (int) substr( $number, -1, 1 );
			$date        = static::get( "Y-01-{$last_number}" );
			$output      = $number . $date->format( 'S' );
		}


		return apply_filters( 'stellarwp/dates/number_to_ordinal', $output, $number );
	}

	/**
	 * Given 2 datetime ranges, return whether the 2nd one occurs during the 1st one
	 * Note: all params should be unix timestamps
	 *
	 * @param integer $range_1_start timestamp for start of the first range
	 * @param integer $range_1_end   timestamp for end of the first range
	 * @param integer $range_2_start timestamp for start of the second range
	 * @param integer $range_2_end   timestamp for end of the second range
	 *
	 * @return bool
	 */
	public static function range_coincides( int $range_1_start, int $range_1_end, int $range_2_start, int $range_2_end ): bool {

		// Initialize the return value
		$range_coincides = false;

		/**
		 * conditions:
		 * range 2 starts during range 1 (range 2 start time is between start and end of range 1 )
		 * range 2 ends during range 1 (range 2 end time is between start and end of range 1 )
		 * range 2 encloses range 1 (range 2 starts before range 1 and ends after range 1)
		 */

		$range_2_starts_during_range_1 = $range_2_start >= $range_1_start && $range_2_start < $range_1_end;
		$range_2_ends_during_range_1   = $range_2_end > $range_1_start && $range_2_end <= $range_1_end;
		$range_2_encloses_range_1      = $range_2_start < $range_1_start && $range_2_end > $range_1_end;

		if ( $range_2_starts_during_range_1 || $range_2_ends_during_range_1 || $range_2_encloses_range_1 ) {
			$range_coincides = true;
		}

		return $range_coincides;

	}

	/**
	 * Accepts a string representing a date/time and attempts to convert it to
	 * the specified format, returning an empty string if this is not possible.
	 *
	 * @param string|int|DateTime|DateTimeImmutable $dt_string
	 * @param string                                $new_format
	 *
	 * @return string
	 */
	public static function reformat( $dt_string, $new_format ): string {
		$date    = static::get( $dt_string );
		$revised = $date->format( $new_format );

		return $revised ?: '';
	}

	/**
	 * Returns as string the nearest half a hour for a given valid string datetime.
	 *
	 * @since 1.0.0
	 *
	 * @param string|int|DateTime|DateTimeImmutable $date Valid DateTime string.
	 *
	 * @return DateTime|DateTimeImmutable Rounded datetime string
	 */
	public static function round_nearest_half_hour( $date ) {
		$date_object     = static::mutable( $date );
		$rounded_minutes = floor( $date_object->format( 'i' ) / 30 ) * 30;

		return static::get( $date_object->format( 'Y-m-d H:' ) . $rounded_minutes . ':00' );
	}

	/**
	 * Returns the seconds only.
	 *
	 * @param string|int|DateTime|DateTimeImmutable $date The date.
	 *
	 * @return string The seconds only.
	 */
	public static function seconds_only( $date ) {
		return static::get( $date )->format( 's' );
	}

	/**
	 * Sets a value in the cache.
	 *
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return mixed
	 */
	public static function set_cache( $key, $value = null ) {
		return static::$cache[ $key ] = $value;
	}

	/**
	 * Sort an array of dates.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $dates     A single array of dates, or dates passed as individual params.
	 *                          Individual dates can be a `strtotime` parsable string, a DateTime object or a timestamp.
	 * @param string $direction 'ASC' or 'DESC' for ascending/descending sorting. Defaults to 'ASC'.
	 *
	 * @return array<DateTime> A sorted array of DateTime objects.
	 */
	public static function sort( array $dates, string $direction = 'ASC' ): array {
		// If we get passed a single array, break it out of the containing array.
		if ( is_array( $dates[0] ) ) {
			$dates = $dates[0];
		}

		// Ensure we're always dealing with date objects here.
		$dates = array_map(
			function( $date ) {
				return self::mutable( $date );
			},
			$dates
		);

		// If anything other than 'DESC' gets passed (or nothing) we sort ascending.
		if ( 'DESC' === $direction ) {
			rsort( $dates );
		} else {
			sort( $dates );
		}

		return $dates;
	}

	/**
	 * Returns the number of seconds (absolute value) between two dates/times.
	 *
	 * @param string|int|DateTime|DateTimeImmutable $date1 The first date.
	 * @param string|int|DateTime|DateTimeImmutable $date2 The second date.
	 *
	 * @return int The number of seconds between the dates.
	 */
	public static function time_between( $date1, $date2 ): int {
		return abs( static::get( $date1 )->getTimestamp() - static::get( $date2 )->getTimestamp() );
	}

	/**
	 * Returns the time only.
	 *
	 * @param string|int|DateTime|DateTimeImmutable $date The date.
	 *
	 * @return string The time only in DB format.
	 */
	public static function time_only( $date ): string {
		$date = static::get( $date );
		return $date->format( self::DBTIMEFORMAT );
	}

	/**
	 * Unescapes date format strings to be used in functions like `date`.
	 *
	 * Double escaping happens when storing a date format in the database.
	 *
	 * @param mixed $date_format A date format string.
	 *
	 * @return mixed Either the original input or an unescaped date format string.
	 */
	public static function unescape_date_format( $date_format ) {
		if ( ! is_string( $date_format ) ) {
			return $date_format;
		}

		// Why so simple? Let's handle other cases as those come up. We have tests in place!
		return str_replace( '\\\\', '\\', $date_format );
	}

	/**
	 * Returns the day of the week the week ends on, expressed as a "w" value
	 * (ie, Sunday is 0 and Saturday is 6).
	 *
	 * @param int $week_starts_on
	 *
	 * @return int
	 */
	public static function week_ends_on( $week_starts_on ): int {
		if ( --$week_starts_on < 0 ) {
			$week_starts_on = 6;
		}
		return $week_starts_on;
	}

	/**
	 * Converts a locally-formatted date to a unix timestamp. This is a drop-in
	 * replacement for `strtotime()`, except that where strtotime assumes GMT, this
	 * assumes local time (as described below). If a timezone is specified, this
	 * function defers to strtotime().
	 *
	 * If there is a timezone_string available, the date is assumed to be in that
	 * timezone, otherwise it simply subtracts the value of the 'gmt_offset'
	 * option.
	 *
	 * @see  strtotime()
	 *
	 * @param string $string A date/time string. See `strtotime` for valid formats
	 *
	 * @return int UNIX timestamp.
	 * @uses get_option() to retrieve the value of 'gmt_offset'
	 *
	 */
	public static function wp_strtotime( $string ) {
		// If there's a timezone specified, we shouldn't convert it
		try {
			$test_date = new DateTime( $string );
			if ( 'UTC' != $test_date->getTimezone()->getName() ) {
				return strtotime( $string );
			}
		} catch ( Exception $e ) {
			return strtotime( $string );
		}

		if ( ! static::get_cache( 'option_timezone_string' ) ) {
			static::set_cache( 'option_timezone_string', get_option( 'timezone_string' ) );
		}
		if ( ! static::get_cache( 'option_gmt_offset' ) ) {
			static::set_cache( 'option_gmt_offset', get_option( 'gmt_offset' ) );
		}

		$tz = static::get_cache( 'option_timezone_string' );
		if ( ! empty( $tz ) ) {
			$date = date_create( $string, new DateTimeZone( $tz ) );
			if ( ! $date ) {
				return strtotime( $string );
			}
			$date->setTimezone( new DateTimeZone( 'UTC' ) );
			return $date->getTimestamp();
		} else {
			$offset    = (float) static::get_cache( 'option_gmt_offset' );
			$seconds   = intval( $offset * HOUR_IN_SECONDS );
			$timestamp = strtotime( $string ) - $seconds;
			return $timestamp;
		}
	}

	/**
	 * Return a WP Locale month in the specified format
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $month  Month of year
	 * @param string     $format Month format: full, month, abbreviation, abbrev, abbr, short
	 *
	 * @return string
	 */
	public static function wp_locale_month( $month, $format = 'month' ) {
		$month = trim( $month );

		$valid_formats = [
			'full',
			'month',
			'abbreviation',
			'abbrev',
			'abbr',
			'short',
		];

		// if there isn't a valid format, bail without providing a localized string
		if ( ! in_array( $format, $valid_formats ) ) {
			return $month;
		}

		if ( empty( self::$localized_months ) ) {
			self::build_localized_months();
		}

		// make sure numeric months are valid
		if ( is_numeric( $month ) ) {
			$month_num = (int) $month;

			// if the month num falls out of range, bail without localizing
			if ( 0 > $month_num || 12 < $month_num ) {
				return $month;
			}
		} else {
			$months = [
				'Jan',
				'Feb',
				'Mar',
				'Apr',
				'May',
				'Jun',
				'Jul',
				'Aug',
				'Sep',
				'Oct',
				'Nov',
				'Dec',
			];

			// convert the provided month to a 3-character month and find it in the months array so we
			// can build an appropriate month number
			$month_num = array_search( ucwords( substr( $month, 0, 3 ) ), $months );

			// if we can't find the provided month in our month list, bail without localizing
			if ( false === $month_num ) {
				return $month;
			}

			// let's increment the num because months start at 01 rather than 00
			$month_num++;
		}

		$month_num = str_pad( (string) $month_num, 2, '0', STR_PAD_LEFT );

		$type = ( 'full' === $format || 'month' === $format ) ? 'full' : 'short';

		return self::$localized_months[ $type ][ $month_num ];
	}

	/**
	 * Return a WP Locale weekday in the specified format
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $weekday Day of week
	 * @param string     $format  Weekday format: full, weekday, initial, abbreviation, abbrev, abbr, short
	 *
	 * @return string
	 */
	public static function wp_locale_weekday( $weekday, $format = 'weekday' ) {
		$weekday = trim( $weekday );

		$valid_formats = [
			'full',
			'weekday',
			'initial',
			'abbreviation',
			'abbrev',
			'abbr',
			'short',
		];

		// if there isn't a valid format, bail without providing a localized string
		if ( ! in_array( $format, $valid_formats ) ) {
			return $weekday;
		}

		if ( empty( self::$localized_weekdays ) ) {
			self::build_localized_weekdays();
		}

		// if the weekday isn't numeric, we need to convert to numeric in order to
		// leverage self::localized_weekdays
		if ( ! is_numeric( $weekday ) ) {
			$days_of_week = [
				'Sun',
				'Mon',
				'Tue',
				'Wed',
				'Thu',
				'Fri',
				'Sat',
			];

			$day_index = array_search( ucwords( substr( $weekday, 0, 3 ) ), $days_of_week );

			if ( false === $day_index ) {
				return $weekday;
			}

			$weekday = $day_index;
		}

		switch ( $format ) {
			case 'initial':
				$type = 'initial';
				break;
			case 'abbreviation':
			case 'abbrev':
			case 'abbr':
			case 'short':
				$type = 'short';
				break;
			case 'weekday':
			case 'full':
			default:
				$type = 'full';
				break;
		}

		return self::$localized_weekdays[ $type ][ $weekday ];
	}
}
