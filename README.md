# express-tracking

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

express tracking 快递单号状态追踪

## Install

Via Composer

``` bash
$ composer require xu42/express-tracking
```

## Usage

``` php
require_once './vendor/autoload.php';
$number = '';
$expressTracking = new \Xu42\ExpressTracking\ExpressTracking($number);
$result = $expressTracking->latestStatus();
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

Tests unavailable.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please using the issue tracker.

## Credits

- [Xu42](https://github.com/xu42)
- [All Contributors](https://github.com/xu42/express-tracking/contributors)

## License

The GPL2.0 License. Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/xu42/express-tracking.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/xu42/express-tracking.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/xu42/express-tracking
[link-downloads]: https://packagist.org/packages/xu42/express-tracking
[link-author]: https://github.com/xu42
[link-contributors]: ../../contributors
