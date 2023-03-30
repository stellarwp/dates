<?php
namespace StellarWP\Dates;

/**
 * Extends DateTimeImmutable and includes translation capabilities.
 *
 * @since 1.0.0
 */

use DateTimeImmutable;

/**
 * Class Date i18n Immutable
 *
 * @since 1.0.0
 */
class Date_I18n_Immutable extends DateTimeImmutable {
	/**
	 * {@inheritDoc}
	 *
	 * @return Date_I18n_Immutable Localizable variation of DateTimeImmutable.
	 */
	#[\ReturnTypeWillChange]
	public static function createFromMutable( $datetime ) {
		$date_object = new self;
		$date_object = $date_object->setTimestamp( $datetime->getTimestamp() );
		$date_object = $date_object->setTimezone( $datetime->getTimezone() );

		return $date_object;
	}

	/**
	 * Returns a translated string using the params from this Immutable DateTime instance.
	 *
	 * @since 1.0.0
	 *
	 * @param string $date_format Format to be used in the translation.
	 *
	 * @return string         Translated date.
	 */
	public function format_i18n( $date_format ) {
		$unix_with_tz = $this->getTimestamp() + $this->getOffset();
		$translated   = date_i18n( $date_format, $unix_with_tz );

		return $translated;
	}
}