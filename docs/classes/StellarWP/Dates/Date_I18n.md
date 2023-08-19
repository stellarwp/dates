***

# Date_I18n

Class Date i18n



* Full name: `\StellarWP\Dates\Date_I18n`
* Parent class: [`DateTime`](../../DateTime.md)




## Methods


### createFromImmutable

{@inheritDoc}

```php
public static createFromImmutable(mixed $datetime): \StellarWP\Dates\Date_I18n
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$datetime` | **mixed** |  |


**Return Value:**

Localizable variation of DateTime.



***

### format_i18n

Returns a translated string using the params from this DateTime instance.

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
