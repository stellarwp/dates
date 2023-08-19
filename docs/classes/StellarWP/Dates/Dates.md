***

# Dates





* Full name: `\StellarWP\Dates\Dates`


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`DATEONLYFORMAT`|public| |&#039;F j, Y&#039;|
|`TIMEFORMAT`|public| |&#039;g:i A&#039;|
|`HOURFORMAT`|public| |&#039;g&#039;|
|`MINUTEFORMAT`|public| |&#039;i&#039;|
|`MERIDIANFORMAT`|public| |&#039;A&#039;|
|`DBDATEFORMAT`|public| |&#039;Y-m-d&#039;|
|`DBDATETIMEFORMAT`|public| |&#039;Y-m-d H:i:s&#039;|
|`DBTZDATETIMEFORMAT`|public| |&#039;Y-m-d H:i:s O&#039;|
|`DBTIMEFORMAT`|public| |&#039;H:i:s&#039;|
|`DBYEARMONTHTIMEFORMAT`|public| |&#039;Y-m&#039;|

## Properties


### localized_months_full



```php
protected static $localized_months_full
```



* This property is **static**.


***

### localized_months_short



```php
protected static $localized_months_short
```



* This property is **static**.


***

### localized_weekdays



```php
protected static $localized_weekdays
```



* This property is **static**.


***

### localized_months



```php
protected static $localized_months
```



* This property is **static**.


***

### cache



```php
public static $cache
```



* This property is **static**.


***

## Methods


### build_date_object

Alias of the mutable() method. Builds a date object from a given datetime and timezone.

```php
public static build_date_object(string|\DateTimeInterface|int $datetime = &#039;now&#039;, string|\DateTimeZone|null $timezone = null, bool $with_fallback = true): \DateTime|false
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$datetime` | **string&#124;\DateTimeInterface&#124;int** | A `strtotime` parsable string, a DateTime object or<br />a timestamp; defaults to `now`. |
| `$timezone` | **string&#124;\DateTimeZone&#124;null** | A timezone string, UTC offset or DateTimeZone object;<br />defaults to the site timezone; this parameter is ignored<br />if the `$datetime` parameter is a DatTime object. |
| `$with_fallback` | **bool** | Whether to return a DateTime object even when the date data is<br />invalid or not; defaults to `true`. |


**Return Value:**

A DateTime object built using the specified date, time and timezone; if `$with_fallback`
is set to `false` then `false` will be returned if a DateTime object could not be built.



***

### build_localized_months

Builds arrays of localized full and short months.

```php
private static build_localized_months(): mixed
```



* This method is **static**.







***

### build_localized_weekdays

Builds arrays of localized full, short and initialized weekdays.

```php
private static build_localized_weekdays(): mixed
```



* This method is **static**.







***

### catch_and_throw

A convenience function used to cast errors to exceptions.

```php
public static catch_and_throw(mixed $errno, mixed $errstr): mixed
```

Use in `set_error_handler` calls:

try{
    set_error_handler( [ __CLASS__, 'catch_and_throw' ] );
    // ...do something that could generate an error...
    restore_error_handler();
} catch ( RuntimeException $e ) {
    // Handle the exception.
}

* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$errno` | **mixed** |  |
| `$errstr` | **mixed** |  |



**See Also:**

* \StellarWP\Dates\set_error_handler() - * \StellarWP\Dates\restore_error_handler() - 

***

### clear_cache

Resets the cache.

```php
public static clear_cache(): mixed
```



* This method is **static**.







***

### date

Alias for `date_only()`. Returns the date only.

```php
public static date(string|int|\DateTime|\DateTimeImmutable $date = &#039;now&#039;, string|null $format = null): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** | The date (timestamp or string). |
| `$format` | **string&#124;null** | The format used |


**Return Value:**

The date only in DB format.



***

### date_diff

Alias for diff(). The number of days between two arbitrary dates.

```php
public static date_diff(string|int|\DateTime|\DateTimeImmutable $date1, string|int|\DateTime|\DateTimeImmutable $date2): int
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date1` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** | The first date. |
| `$date2` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** | The second date. |


**Return Value:**

The number of days between two dates.



***

### date_only

Returns the date only.

```php
public static date_only(string|int|\DateTime|\DateTimeImmutable $date = &#039;now&#039;, bool $isTimestamp = false, string|null $format = null): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** | The date (timestamp or string). |
| `$isTimestamp` | **bool** | Is $date in timestamp format? |
| `$format` | **string&#124;null** | The format used |


**Return Value:**

The date only in DB format.



***

### datetime_from_format

As PHP 5.2 doesn't have a good version of `date_parse_from_format`, this is how we deal with
possible weird datepicker formats not working

```php
public static datetime_from_format(string $format, string $date): string|bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$format` | **string** | The weird format you are using |
| `$date` | **string** | The date string to parse |


**Return Value:**

A DB formated Date, includes time if possible



***

### diff

The number of days between two arbitrary dates.

```php
public static diff(string|int|\DateTime|\DateTimeImmutable $date1, string|int|\DateTime|\DateTimeImmutable $date2): int
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date1` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** | The first date. |
| `$date2` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** | The second date. |


**Return Value:**

The number of days between two dates.



***

### first_day_in_month

Returns the weekday of the 1st day of the month in
"w" format (ie, Sunday is 0 and Saturday is 6) or
false if this cannot be established.

```php
public static first_day_in_month(string|int|\DateTime|\DateTimeImmutable $month): \DateTime|\DateTimeImmutable|bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$month` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** |  |




***

### get

Builds a date object from a given datetime and timezone.

```php
public static get(string|\DateTimeInterface|int $datetime = &#039;now&#039;, string|\DateTimeZone|null $timezone = null, bool $with_fallback = true, bool $immutable = true): \DateTime|\DateTimeImmutable|false
```

Defaults to immutable, but can be set to return a mutable DateTime object.

* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$datetime` | **string&#124;\DateTimeInterface&#124;int** | A `strtotime` parsable string, a DateTime object or<br />a timestamp; defaults to `now`. |
| `$timezone` | **string&#124;\DateTimeZone&#124;null** | A timezone string, UTC offset or DateTimeZone object;<br />defaults to the site timezone; this parameter is ignored<br />if the `$datetime` parameter is a DatTime object. |
| `$with_fallback` | **bool** | Whether to return a DateTime object even when the date data is<br />invalid or not; defaults to `true`. |
| `$immutable` | **bool** | Whether to return a DateTimeImmutable object or a DateTime object; |


**Return Value:**

A DateTime object built using the specified date, time and timezone; if `$with_fallback`
is set to `false` then `false` will be returned if a DateTime object could not be built.



***

### get_cache

Gets a value from the cache.

```php
public static get_cache(string $key, mixed $default = null): mixed
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | **string** |  |
| `$default` | **mixed** |  |




***

### get_first_day_of_week_in_month

Gets the first day of the week in a month (ie the first Tuesday).

```php
public static get_first_day_of_week_in_month(string|int|\DateTime|\DateTimeImmutable $curdate, int $day_of_week): \DateTime|\DateTimeImmutable
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$curdate` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** | A timestamp. |
| `$day_of_week` | **int** | The index of the day of the week. |


**Return Value:**

The date that fits the qualifications.



***

### get_last_day_of_month

Returns the last day of the month given a php date.

```php
public static get_last_day_of_month(string|int|\DateTime|\DateTimeImmutable $timestamp): \DateTime|\DateTimeImmutable
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$timestamp` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** | The timestamp. |


**Return Value:**

The last day of the month.



***

### get_last_day_of_week_in_month

Gets the last day of the week in a month (ie the last Tuesday).  Passing in -1 gives you the last day in the month.

```php
public static get_last_day_of_week_in_month(string|int|\DateTime|\DateTimeImmutable $curdate, int $day_of_week): \DateTime|\DateTimeImmutable
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$curdate` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** | A timestamp. |
| `$day_of_week` | **int** | The index of the day of the week. |


**Return Value:**

The timestamp of the date that fits the qualifications.



***

### get_localized_months_full

Returns an array of localized full month names.

```php
public static get_localized_months_full(): array
```



* This method is **static**.







***

### get_localized_months_short

Returns an array of localized short month names.

```php
public static get_localized_months_short(): array
```



* This method is **static**.







***

### get_localized_weekdays_full

Returns an array of localized full week day names.

```php
public static get_localized_weekdays_full(): array
```



* This method is **static**.







***

### get_localized_weekdays_initial

Returns an array of localized week day initials.

```php
public static get_localized_weekdays_initial(): array
```



* This method is **static**.







***

### get_localized_weekdays_short

Returns an array of localized short week day names.

```php
public static get_localized_weekdays_short(): array
```



* This method is **static**.







***

### get_modifier_from_offset

Accepts a numeric offset (such as "4" or "-6" as stored in the gmt_offset
option) and converts it to a strtotime() style modifier that can be used
to adjust a DateTime object, etc.

```php
public static get_modifier_from_offset( $offset): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$offset` | **** |  |




***

### get_week_start_end

Returns the DateTime object representing the start of the week for a date.

```php
public static get_week_start_end(string|int|\DateTime $date, int|null $start_of_week = null): array
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date` | **string&#124;int&#124;\DateTime** | The date string, timestamp or object. |
| `$start_of_week` | **int&#124;null** | The number representing the start of week day as handled by<br />WordPress: `0` (for Sunday) through `6` (for Saturday). |


**Return Value:**

An array of objects representing the week start and end days, or `false` if the
supplied date is invalid. The timezone of the returned object is set to the site one.
The week start has its time set to `00:00:00`, the week end will have its time set
`23:59:59`.



***

### get_weekday_timestamp

Gets the timestamp of a day in week, month and year context.

```php
public static get_weekday_timestamp(int $day_of_week, int $week_in_month, int $month, int $year, int $week_direction = 1): int|bool
```

Kudos to [icedwater StackOverflow user](http://stackoverflow.com/users/1091386/icedwater) in
[his answer](http://stackoverflow.com/questions/924246/get-the-first-or-last-friday-in-a-month).

Usage examples:
"The second Wednesday of March 2015" - `get_day_timestamp( 3, 2, 3, 2015, 1)`
"The last Friday of December 2015" - `get_day_timestamp( 5, 1, 12, 2015, -1)`
"The first Monday of April 2016 - `get_day_timestamp( 1, 1, 4, 2016, 1)`
"The penultimate Thursday of January 2012" - `get_day_timestamp( 4, 2, 1, 2012, -1)`

* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$day_of_week` | **int** | The day representing the number in the week, Monday is `1`, Tuesday is `2`, Sunday is `7` |
| `$week_in_month` | **int** | The week number in the month; first week is `1`, second week is `2`; when direction is reverse<br />then `1` is last week of the month, `2` is penultimate week of the month and so on. |
| `$month` | **int** | The month number in the year, January is `1` |
| `$year` | **int** | The year number, e.g. &quot;2015&quot; |
| `$week_direction` | **int** | Either `1` or `-1`; the direction for the search referring to the week, defaults to `1`<br />to specify weeks in natural order so:<br />$week_direction `1` and $week_in_month `1` means &quot;first week of the month&quot;<br />$week_direction `1` and $week_in_month `3` means &quot;third week of the month&quot;<br />$week_direction `-1` and $week_in_month `1` means &quot;last week of the month&quot;<br />$week_direction `-1` and $week_in_month `2` means &quot;penultimmate week of the month&quot; |


**Return Value:**

The day timestamp



***

### has_cache

Determines if a cache value exists.

```php
public static has_cache(string $key): bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | **string** |  |




***

### hour

Alias of `hour_only()`. Returns the hour only.

```php
public static hour(string|int|\DateTime|\DateTimeImmutable $date = &#039;now&#039;, bool $use_24_hour = false): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** | The date. |
| `$use_24_hour` | **bool** | Whether to use 24 hour format. |


**Return Value:**

The hour only.



***

### hour_only

Returns the hour only.

```php
public static hour_only(string|int|\DateTime|\DateTimeImmutable $date = &#039;now&#039;, bool $use_24_hour = false): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** | The date. |
| `$use_24_hour` | **bool** | Whether to use 24 hour format. |


**Return Value:**

The hour only.



***

### immutable

Builds the immutable version of a date from a string, integer (timestamp) or \DateTime object.

```php
public static immutable(string|\DateTimeInterface|int $datetime = &#039;now&#039;, string|\DateTimeZone|null $timezone = null, bool $with_fallback = true): \DateTimeImmutable|false
```

It's the immutable version of the `Dates::mutable` method.

* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$datetime` | **string&#124;\DateTimeInterface&#124;int** | A `strtotime` parsable string, a DateTime object or<br />a timestamp; defaults to `now`. |
| `$timezone` | **string&#124;\DateTimeZone&#124;null** | A timezone string, UTC offset or DateTimeZone object;<br />defaults to the site timezone; this parameter is ignored<br />if the `$datetime` parameter is a DatTime object. |
| `$with_fallback` | **bool** | Whether to return a DateTime object even when the date data is<br />invalid or not; defaults to `true`. |


**Return Value:**

A DateTime object built using the specified date, time and timezone; if
`$with_fallback` is set to `false` then `false` will be returned if a
DateTime object could not be built.



***

### interval

Builds and returns a `DateInterval` object from the interval specification.

```php
public static interval(mixed $interval_spec): \DateInterval
```

For performance purposes the use of `DateInterval` specifications is preferred, so `P1D` is better than
`1 day`.

* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$interval_spec` | **mixed** |  |


**Return Value:**

The built date interval object.



***

### is_between

Determine if the given date is between two dates.

```php
public static is_between(string|\DateTimeInterface|int $date, string|\DateTimeInterface|int $start_date, string|\DateTimeInterface|int $end_date): bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date` | **string&#124;\DateTimeInterface&#124;int** | A `strtotime` parsable string, a DateTime object or a timestamp. |
| `$start_date` | **string&#124;\DateTimeInterface&#124;int** | A `strtotime` parsable string, a DateTime object or a timestamp. |
| `$end_date` | **string&#124;\DateTimeInterface&#124;int** | A `strtotime` parsable string, a DateTime object or a timestamp. |


**Return Value:**

Whether the current datetime (or passed "now") is between the passed start and end dates.



***

### is_now

Determine if "now" is between two dates. Calls `is_date_between()` with a different parameter order.

```php
public static is_now(string|\DateTimeInterface|int $start_date, string|\DateTimeInterface|int $end_date, string|\DateTimeInterface|int $now = &#039;now&#039;): bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$start_date` | **string&#124;\DateTimeInterface&#124;int** | A `strtotime` parsable string, a DateTime object or a timestamp. |
| `$end_date` | **string&#124;\DateTimeInterface&#124;int** | A `strtotime` parsable string, a DateTime object or a timestamp. |
| `$now` | **string&#124;\DateTimeInterface&#124;int** | A `strtotime` parsable string, a DateTime object or a timestamp. Defaults to &#039;now&#039;. |


**Return Value:**

Whether the current datetime (or passed "now") is between the passed start and end dates.



***

### is_timestamp

check if a given string is a timestamp

```php
public static is_timestamp( $timestamp): bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$timestamp` | **** |  |




***

### is_valid

Validates a date string to make sure it can be used to build DateTime objects.

```php
public static is_valid(string $date): bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date` | **string** | The date string that should validated. |


**Return Value:**

Whether the date string can be used to build DateTime objects, and is thus parsable by functions
like `strtotime`, or not.



***

### is_valid_date

Alias of `is_valid()`. Validates a date string to make sure it can be used to build DateTime objects.

```php
public static is_valid_date(string $date): bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date` | **string** | The date string that should validated. |


**Return Value:**

Whether the date string can be used to build DateTime objects, and is thus parsable by functions
like `strtotime`, or not.



***

### is_weekday

Returns true if the timestamp is a weekday.

```php
public static is_weekday(string|int|\DateTime|\DateTimeImmutable $curdate): bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$curdate` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** | A timestamp or date. |


**Return Value:**

If the timestamp is a weekday.



***

### is_weekend

Returns true if the timestamp is a weekend.

```php
public static is_weekend(string|int|\DateTime|\DateTimeImmutable $curdate): bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$curdate` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** | A timestamp or date. |


**Return Value:**

If the timestamp is a weekend.



***

### last_day_in_month

Returns the weekday of the last day of the month in
"w" format (ie, Sunday is 0 and Saturday is 6) or
false if this cannot be established.

```php
public static last_day_in_month(string|int|\DateTime|\DateTimeImmutable $month): \DateTime|\DateTimeImmutable|bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$month` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** |  |




***

### meridian

Alias for `meridian_only()`. Returns the meridian (am or pm) only.

```php
public static meridian(string|int|\DateTime|\DateTimeImmutable $date = &#039;now&#039;): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** | The date. |


**Return Value:**

The meridian only in DB format.



***

### meridian_only

Returns the meridian (am or pm) only.

```php
public static meridian_only(string|int|\DateTime|\DateTimeImmutable $date = &#039;now&#039;): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** | The date. |


**Return Value:**

The meridian only in DB format.



***

### minutes

Alias for `minutes_only`. Returns the meridian (am or pm) only.

```php
public static minutes(string|int|\DateTime|\DateTimeImmutable $date = &#039;now&#039;): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** | The date. |


**Return Value:**

The meridian only in DB format.



***

### minutes_only

Returns the minute only.

```php
public static minutes_only(string|int|\DateTime|\DateTimeImmutable $date = &#039;now&#039;): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** | The date. |


**Return Value:**

The minute only.



***

### mutable

Builds a date object from a given datetime and timezone.

```php
public static mutable(string|\DateTimeInterface|int $datetime = &#039;now&#039;, string|\DateTimeZone|null $timezone = null, bool $with_fallback = true): \DateTime|false
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$datetime` | **string&#124;\DateTimeInterface&#124;int** | A `strtotime` parsable string, a DateTime object or<br />a timestamp; defaults to `now`. |
| `$timezone` | **string&#124;\DateTimeZone&#124;null** | A timezone string, UTC offset or DateTimeZone object;<br />defaults to the site timezone; this parameter is ignored<br />if the `$datetime` parameter is a DatTime object. |
| `$with_fallback` | **bool** | Whether to return a DateTime object even when the date data is<br />invalid or not; defaults to `true`. |


**Return Value:**

A DateTime object built using the specified date, time and timezone; if `$with_fallback`
is set to `false` then `false` will be returned if a DateTime object could not be built.



***

### number_to_ordinal

From http://php.net/manual/en/function.date.php

```php
public static number_to_ordinal(int $number): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$number` | **int** | A number. |


**Return Value:**

The ordinal for that number.



***

### range_coincides

Alias for range_overlaps(). Given 2 datetime ranges, return whether the 2nd one occurs during the 1st one
Note: all params should be unix timestamps

```php
public static range_coincides(string|\DateTimeInterface|int $range_1_start, string|\DateTimeInterface|int $range_1_end, string|\DateTimeInterface|int $range_2_start, string|\DateTimeInterface|int $range_2_end): bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$range_1_start` | **string&#124;\DateTimeInterface&#124;int** | timestamp, dates string, or DateTimeInterface for start of the first range |
| `$range_1_end` | **string&#124;\DateTimeInterface&#124;int** | timestamp, dates string, or DateTimeInterface for end of the first range |
| `$range_2_start` | **string&#124;\DateTimeInterface&#124;int** | timestamp, dates string, or DateTimeInterface for start of the second range |
| `$range_2_end` | **string&#124;\DateTimeInterface&#124;int** | timestamp, dates string, or DateTimeInterface for end of the second range |




***

### range_overlaps

Given 2 datetime ranges, return whether the 2nd one occurs during the 1st one
Note: all params should be unix timestamps

```php
public static range_overlaps(string|\DateTimeInterface|int $range_1_start, string|\DateTimeInterface|int $range_1_end, string|\DateTimeInterface|int $range_2_start, string|\DateTimeInterface|int $range_2_end): bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$range_1_start` | **string&#124;\DateTimeInterface&#124;int** | timestamp, dates string, or DateTimeInterface for start of the first range |
| `$range_1_end` | **string&#124;\DateTimeInterface&#124;int** | timestamp, dates string, or DateTimeInterface for end of the first range |
| `$range_2_start` | **string&#124;\DateTimeInterface&#124;int** | timestamp, dates string, or DateTimeInterface for start of the second range |
| `$range_2_end` | **string&#124;\DateTimeInterface&#124;int** | timestamp, dates string, or DateTimeInterface for end of the second range |




***

### reformat

Accepts a string representing a date/time and attempts to convert it to
the specified format, returning an empty string if this is not possible.

```php
public static reformat(string|int|\DateTime|\DateTimeImmutable $dt_string, string $new_format): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$dt_string` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** |  |
| `$new_format` | **string** |  |




***

### round_nearest_half_hour

Returns as string the nearest half a hour for a given valid string datetime.

```php
public static round_nearest_half_hour(string|int|\DateTime|\DateTimeImmutable $date): \DateTime|\DateTimeImmutable
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** | Valid DateTime string. |


**Return Value:**

Rounded datetime string



***

### seconds

Alias for `seconds_only()`. Returns the seconds only.

```php
public static seconds(string|int|\DateTime|\DateTimeImmutable $date = &#039;now&#039;): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** | The date. |


**Return Value:**

The seconds only.



***

### seconds_only

Returns the seconds only.

```php
public static seconds_only(string|int|\DateTime|\DateTimeImmutable $date = &#039;now&#039;): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** | The date. |


**Return Value:**

The seconds only.



***

### set_cache

Sets a value in the cache.

```php
public static set_cache(string $key, mixed $value = null): mixed
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | **string** |  |
| `$value` | **mixed** |  |




***

### sort

Sort an array of dates.

```php
public static sort(array $dates, string $direction = &#039;ASC&#039;): \DateTime[]
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$dates` | **array** | A single array of dates, or dates passed as individual params.<br />Individual dates can be a `strtotime` parsable string, a DateTime object or a timestamp. |
| `$direction` | **string** | &#039;ASC&#039; or &#039;DESC&#039; for ascending/descending sorting. Defaults to &#039;ASC&#039;. |


**Return Value:**

A sorted array of DateTime objects.



***

### time_between

Returns the number of seconds (absolute value) between two dates/times.

```php
public static time_between(string|int|\DateTime|\DateTimeImmutable $date1, string|int|\DateTime|\DateTimeImmutable $date2): int
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date1` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** | The first date. |
| `$date2` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** | The second date. |


**Return Value:**

The number of seconds between the dates.



***

### time

Alias for `time_only()`. Returns the time only.

```php
public static time(string|int|\DateTime|\DateTimeImmutable $date = &#039;now&#039;): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** | The date. |


**Return Value:**

The time only in DB format.



***

### time_only

Returns the time only.

```php
public static time_only(string|int|\DateTime|\DateTimeImmutable $date = &#039;now&#039;): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date` | **string&#124;int&#124;\DateTime&#124;\DateTimeImmutable** | The date. |


**Return Value:**

The time only in DB format.



***

### unescape_date_format

Unescapes date format strings to be used in functions like `date`.

```php
public static unescape_date_format(mixed $date_format): mixed
```

Double escaping happens when storing a date format in the database.

* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date_format` | **mixed** | A date format string. |


**Return Value:**

Either the original input or an unescaped date format string.



***

### week_ends_on

Returns the day of the week the week ends on, expressed as a "w" value
(ie, Sunday is 0 and Saturday is 6).

```php
public static week_ends_on(int $week_starts_on): int
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$week_starts_on` | **int** |  |




***

### wp_strtotime

Converts a locally-formatted date to a unix timestamp. This is a drop-in
replacement for `strtotime()`, except that where strtotime assumes GMT, this
assumes local time (as described below). If a timezone is specified, this
function defers to strtotime().

```php
public static wp_strtotime(string $string): int
```

If there is a timezone_string available, the date is assumed to be in that
timezone, otherwise it simply subtracts the value of the 'gmt_offset'
option.

* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$string` | **string** | A date/time string. See `strtotime` for valid formats |


**Return Value:**

UNIX timestamp.


**See Also:**

* \StellarWP\Dates\strtotime() - 

***

### wp_locale_month

Return a WP Locale month in the specified format

```php
public static wp_locale_month(int|string $month, string $format = &#039;month&#039;): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$month` | **int&#124;string** | Month of year |
| `$format` | **string** | Month format: full, month, abbreviation, abbrev, abbr, short |




***

### wp_locale_weekday

Return a WP Locale weekday in the specified format

```php
public static wp_locale_weekday(int|string $weekday, string $format = &#039;weekday&#039;): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$weekday` | **int&#124;string** | Day of week |
| `$format` | **string** | Weekday format: full, weekday, initial, abbreviation, abbrev, abbr, short |




***


***
> Automatically generated from source code comments on 2023-08-19 using [phpDocumentor](http://www.phpdoc.org/) and [saggre/phpdocumentor-markdown](https://github.com/Saggre/phpDocumentor-markdown)
