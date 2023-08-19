***

# Timezones

Helpers for handling timezone based event datetimes.

In our timezone logic, the term "local" refers to the locality of an event
rather than the local WordPress timezone.

* Full name: `\StellarWP\Dates\Timezones`


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`SITE_TIMEZONE`|public| |&#039;site&#039;|

## Properties


### timezones

Container for reusable DateTimeZone objects.

```php
protected static array $timezones
```



* This property is **static**.


***

## Methods


### init



```php
public static init(): mixed
```



* This method is **static**.







***

### abbr

Attempts to provide the correct timezone abbreviation for the provided timezone string
on the date given (and so should account for daylight saving time, etc).

```php
public static abbr(string|\DateTime|\DateTimeImmutable $date, string|\DateTimeZone $timezone_string): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date` | **string&#124;\DateTime&#124;\DateTimeImmutable** | The date string representation or object. |
| `$timezone_string` | **string&#124;\DateTimeZone** | The timezone string or object. |




***

### apply_offset

Applies an time offset to the specified date time.

```php
public static apply_offset(string $datetime, int|string $offset, bool $invert = false): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$datetime` | **string** | The date and time string in a valid date format. |
| `$offset` | **int&#124;string** | (string or numeric offset) |
| `$invert` | **bool** | = false Whether the offset should be added (`true`) or<br />subtracted (`false`); signum operations carry over so<br />`-(-23) = +23`. |




***

### build_timezone_object

Returns a valid timezone object built from the passed timezone or from the
site one if a timezone in not passed.

```php
public static build_timezone_object(string|null|\DateTimeZone $timezone = null): \DateTimeZone
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$timezone` | **string&#124;null&#124;\DateTimeZone** | A DateTimeZone object, a timezone string<br />or `null` to build an object using the site one. |


**Return Value:**

The built DateTimeZone object.



***

### clear_site_timezone_abbr

Wipe the cached site timezone abbreviation, if set.

```php
public static clear_site_timezone_abbr(mixed $option_val): mixed
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$option_val` | **mixed** | (passed through without modification) |




***

### convert_date_from_timezone

Converts a date string or timestamp to a destination timezone.

```php
public static convert_date_from_timezone(string|int $date, string $from_timezone, string $to_timezone, string $format): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date` | **string&#124;int** | Either a string parseable by the `strtotime` function or a UNIX timestamp. |
| `$from_timezone` | **string** | The timezone of the source date. |
| `$to_timezone` | **string** | The timezone the destination date should use. |
| `$format` | **string** | The format that should be used for the destination date. |


**Return Value:**

The formatted and converted date.



***

### generate_timezone_string_from_utc_offset

Helper function to retrieve the timezone string for a given UTC offset

```php
public static generate_timezone_string_from_utc_offset(string $offset): string
```

This is a close copy of WooCommerce's wc_timezone_string() method

* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$offset` | **string** | UTC offset |




***

### get_system_timezone

Gets the system timezone.

```php
public static get_system_timezone(): \DateTimeZone
```



* This method is **static**.







***

### get_timezone

Returns a DateTimeZone object matching the representation in $tzstring where
possible, or else representing UTC (or, in the worst case, false).

```php
public static get_timezone(string $tzstring, bool $with_fallback = true): \DateTimeZone|false
```

If optional parameter $with_fallback is true, which is the default, then in
the event it cannot find/create the desired timezone it will try to return the
UTC DateTimeZone before bailing.

* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$tzstring` | **string** |  |
| `$with_fallback` | **bool** | = true |




***

### get_valid_timezone

Parses the timezone string to validate or convert it into a valid one.

```php
public static get_valid_timezone(string|\DateTimeZone $timezone_candidate): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$timezone_candidate` | **string&#124;\DateTimeZone** | The timezone string candidate. |


**Return Value:**

The validated timezone string or a valid timezone string alternative.



***

### invalidate_caches

Clear any cached timezone-related values when appropriate.

```php
protected static invalidate_caches(): mixed
```

Currently we are concerned only with the site timezone abbreviation.

* This method is **static**.







***

### is_mode

Confirms if the current timezone mode matches the $possible_mode.

```php
public static is_mode(string $possible_mode): bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$possible_mode` | **string** |  |




***

### is_utc_offset

Tests to see if the timezone string is a UTC offset, ie "UTC+2".

```php
public static is_utc_offset(string $timezone): bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$timezone` | **string** |  |




***

### is_valid_timezone

Whether the candidate timezone is a valid PHP timezone or a supported UTC offset.

```php
public static is_valid_timezone(string $candidate): bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$candidate` | **string** |  |




***

### localize_date

Localizes a date or timestamp using WordPress timezone and returns it in the specified format.

```php
public static localize_date(string $format = null, string|int $date = null, string $timezone = null): string|bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$format` | **string** | The format the date shouuld be formatted to. |
| `$date` | **string&#124;int** | The date UNIX timestamp or `strtotime` parseable string. |
| `$timezone` | **string** | An optional timezone string identifying the timezone the date shoudl be localized<br />to; defaults to the WordPress installation timezone (if available) or to the system<br />timezone. |


**Return Value:**

The parsed date in the specified format and localized to the system or specified
timezone, or `false` if the specified date is not a valid date string or timestamp
or the specified timezone is not a valid timezone string.



***

### maybe_get_tz_name

Try to figure out the Timezone name base on offset

```php
public static maybe_get_tz_name(string|int|float $timezone): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$timezone` | **string&#124;int&#124;float** | The timezone |


**Return Value:**

The Guessed Timezone String



***

### mode

Returns a string representing the timezone/offset currently desired for
the display of dates and times.

```php
public static mode(): string
```



* This method is **static**.







***

### timezone_from_utc_offset

Given a string in the form "UTC+2.5" returns the corresponding DateTimeZone object.

```php
public static timezone_from_utc_offset(string $utc_offset_string): \DateTimeZone
```

If this is not possible or if $utc_offset_string does not match the expected pattern,
boolean false is returned.

* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$utc_offset_string` | **string** |  |


**Return Value:**

| bool



***

### to_tz

Tries to convert the provided $datetime to the timezone represented by $tzstring.

```php
public static to_tz(string $datetime, string $tzstring): string
```

This is the sister function of self::to_utc() - please review the docs for that method
for more information.

* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$datetime` | **string** |  |
| `$tzstring` | **string** |  |




***

### to_utc

Tries to convert the provided $datetime to UTC from the timezone represented by $tzstring.

```php
public static to_utc(string $datetime, string $tzstring, string $format = null): string
```

Though the usual range of formats are allowed, $datetime ordinarily ought to be something
like the "Y-m-d H:i:s" format (ie, no timezone information). If it itself contains timezone
data, the results may be unexpected.

In those cases where the conversion fails to take place, the $datetime string will be
returned untouched.

* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$datetime` | **string** |  |
| `$tzstring` | **string** |  |
| `$format` | **string** | The optional format of the resulting date, defaults to<br />`Dates::DBDATETIMEFORMAT`. |




***

### wp_timezone_abbr

Returns the current site-wide timezone string abbreviation, if it can be
determined or falls back on the full timezone string/offset text.

```php
public static wp_timezone_abbr(string $date): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date` | **string** |  |




***

### wp_timezone_string

Returns the current site-wide timezone string.

```php
public static wp_timezone_string(): string
```

Based on the core WP code found in wp-admin/options-general.php.

* This method is **static**.







***


***
> Automatically generated from source code comments on 2023-08-19 using [phpDocumentor](http://www.phpdoc.org/) and [saggre/phpdocumentor-markdown](https://github.com/Saggre/phpDocumentor-markdown)
