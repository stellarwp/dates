***

# Date_I18n_Immutable

Class Date i18n Immutable



* Full name: `\StellarWP\Dates\Date_I18n_Immutable`
* Parent class: [`DateTimeImmutable`](../../DateTimeImmutable.md)




## Methods


### createFromMutable

{@inheritDoc}

```php
public static createFromMutable(mixed $datetime): \StellarWP\Dates\Date_I18n_Immutable
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$datetime` | **mixed** |  |


**Return Value:**

Localizable variation of DateTimeImmutable.



***

### format_i18n

Returns a translated string using the params from this Immutable DateTime instance.

```php
public format_i18n(string $date_format): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$date_format` | **string** | Format to be used in the translation. |


**Return Value:**

Translated date.



***


***
> Automatically generated from source code comments on 2023-08-19 using [phpDocumentor](http://www.phpdoc.org/) and [saggre/phpdocumentor-markdown](https://github.com/Saggre/phpDocumentor-markdown)
