# Foodlite

Laravel package for multi-provider food API integrations with a unified driver interface.

Zomato is the first built-in driver (via [`tims/zomato-php-sdk`](https://github.com/timslabs/zomato-php-sdk)). Additional drivers can be added later or registered with `Foodlite::extend()`.

## Requirements

- PHP 8.2+
- Laravel 10, 11, or 12
- A Zomato API key for the Zomato driver

## Installation

```bash
composer require tims/foodlite
```

Publish the config:

```bash
php artisan vendor:publish --tag=foodlite-config
```

## Configuration

```env
FOODLITE_DRIVER=zomato
ZOMATO_USER_KEY=your-api-key
# optional
# ZOMATO_BASE_URL=https://developers.zomato.com/api/v2.1
```

```php
// config/foodlite.php
return [
    'default' => env('FOODLITE_DRIVER', 'zomato'),
    'drivers' => [
        'zomato' => [
            'user_key' => env('ZOMATO_USER_KEY'),
            'base_url' => env('ZOMATO_BASE_URL'),
            'guzzle' => [],
        ],
    ],
];
```

## Usage

```php
use Tims\Foodlite\Facades\Foodlite;

// Default driver (zomato)
$categories = Foodlite::driver()->categories();

// Explicit driver
$results = Foodlite::driver('zomato')->search([
    'q' => 'pizza',
    'lat' => 28.6139,
    'lon' => 77.2090,
    'count' => 10,
]);

$restaurant = Foodlite::driver('zomato')->restaurant(16774318);

// Underlying PHP SDK when you need it
$client = Foodlite::driver('zomato')->client();
```

### Zomato driver methods

| Method | Description |
|--------|-------------|
| `categories()` | List categories |
| `cities($params)` | Find cities |
| `collections($params)` | City collections |
| `cuisines($params)` | Cuisines in a city |
| `establishments($params)` | Establishment types |
| `geocode($lat, $lon)` | Location from coordinates |
| `locations($query, $params)` | Search locations |
| `locationDetails($entityId, $entityType)` | Location details |
| `restaurant($resId)` | Restaurant details |
| `dailyMenu($resId)` | Daily menu |
| `reviews($resId, $params)` | Reviews |
| `search($params)` | Search restaurants |
| `client()` | Raw `ZomatoClient` |

## Custom drivers

```php
use Tims\Foodlite\Facades\Foodlite;
use Tims\Foodlite\Drivers\AbstractDriver;

Foodlite::extend('swiggy', function ($app) {
    return new class(config('foodlite.drivers.swiggy', [])) extends AbstractDriver {
        public function getName(): string
        {
            return 'swiggy';
        }

        // your API methods…
    };
});

Foodlite::driver('swiggy');
```

Register custom drivers from a service provider `boot()` method.

## Architecture

```
tims/zomato-php-sdk   → framework-agnostic PHP SDK
tims/foodlite         → Laravel manager + drivers (wraps SDKs)
```

Plain PHP apps keep using the SDK directly. Laravel apps use Foodlite.

## Testing

```bash
composer test
```

## License

MIT
