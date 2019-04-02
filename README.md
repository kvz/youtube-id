# YouTube like ID

## Translates a number to a short alphanumeric version

### Requires:

* PHP 7.0+

### Installation:

```bash
composer require kvz/youtube-id 
```

### Usage:

> **Don't forget to:**
>
> `use Kvz\YoutubeId\Converter;`

Convert any number up to 9007199254740992 to a shorter version in letters e.g.:

```php
Converter::toAlphanumeric(2188847690240); // C7nXQpS
```

Convert back from short version in letters to numbers:

```php
Converter::toNumeric('C7nXQpS'); // 2188847690240
```


If you want the alphaID to be at least 3 letter long, use the `$padUp` argument.

> In most cases this is better than totally random ID generators because this can easily avoid duplicate ID's.
>
> For example if you correlate the alpha ID to an auto incrementing ID in your database, you're done.

```php
Converter::toAlphanumeric(2188847690240, 3); // C7nXQpS
Converter::toNumeric('C7nXQpS', 3); // 2188847686396
```


Although this function's purpose is to just make the ID short - and not so much secure, with third argument `secureKey` you can optionally supply a password to make it harder to calculate the corresponding numeric ID.

```php
Converter::toAlphanumeric(1327301435881, 3, 'Shfu388291ssD'); // C7nXQpS
Converter::toNumeric('C7nXQpS', 3, 'Shfu388291ssD'); // 1327301435881
```


And, for final, you can easy transform alphanumeric result:

```php
Converter::toAlphanumeric(2188847690240, 0, null, Converter::TRANSFORM_UPPERCASE); // C7NXQPS
Converter::toAlphanumeric(2188847690240, 0, null, Converter::TRANSFORM_LOWERCASE); // c7nxqps
```
