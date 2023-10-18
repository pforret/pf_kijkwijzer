# PHP Component to retrieve Kijkwijzer/Cinecheck ratings for movies

Github: 
![GitHub tag](https://img.shields.io/github/v/tag/pforret/pf_kijkwijzer)
![Tests](https://github.com/pforret/pf_kijkwijzer/workflows/Run%20Tests/badge.svg)
![Psalm](https://github.com/pforret/pf_kijkwijzer/workflows/Detect%20Psalm%20warnings/badge.svg)

Packagist: 
[![Packagist Version](https://img.shields.io/packagist/v/pforret/pf_kijkwijzer.svg?style=flat-square)](https://packagist.org/packages/pforret/pf_kijkwijzer)
[![Packagist Downloads](https://img.shields.io/packagist/dt/pforret/pf_kijkwijzer.svg?style=flat-square)](https://packagist.org/packages/pforret/pf_kijkwijzer)

![](assets/logo.jpg)

## Installation

You can install the package via composer:

```bash
composer require pforret/pf_kijkwijzer
```

## Usage

``` php
$obj = new Pforret\PfKijkwijzer();
$movies = $obj->search('Top Gun!');
$movie = $obj->first('Avatar', 2009, 5, PfKijkwijzer::FILTER_MOVIES);
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email author_email instead of using the issue tracker.

## Credits

- [pforret](https://github.com/pforret)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
