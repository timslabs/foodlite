# Foodlite

Laravel package for multi-provider food API integrations with a unified driver interface — Socialite-style.

```php
Foodlite::driver('zomato')->search([...]);
Foodlite::driver('zomato-pos')->confirmOrder([...]);
```

## Drivers

| Driver | Package | Purpose |
|--------|---------|---------|
| `zomato` | [`tims/zomato-php-sdk`](https://packagist.org/packages/tims/zomato-php-sdk) | Restaurant API v2.1 (discovery / search) |
| `zomato-pos` | [`tims/zomato-pos-php-sdk`](https://packagist.org/packages/tims/zomato-pos-php-sdk) | POS Integration (menu, orders, outlets) |

## Requirements

- PHP 8.2+
- Laravel 10, 11, or 12
- API credentials for the drivers you enable

## Installation

```bash
composer require tims/foodlite
```

Publish the config:

```bash
php artisan vendor:publish --tag=foodlite-config
```

## Configuration

Add credentials for each provider your application uses (same idea as Socialite providers in `config/services.php`):

```env
FOODLITE_DRIVER=zomato

# zomato — Restaurant API
ZOMATO_USER_KEY=your-user-key
# ZOMATO_BASE_URL=https://developers.zomato.com/api/v2.1

# zomato-pos — POS Integration API
ZOMATO_POS_API_KEY=your-pos-api-key
# ZOMATO_POS_BASE_URL=https://api.zomato.com
# ZOMATO_POS_API_KEY_HEADER=api-key
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
        'zomato-pos' => [
            'api_key' => env('ZOMATO_POS_API_KEY'),
            'base_url' => env('ZOMATO_POS_BASE_URL'),
            'api_key_header' => env('ZOMATO_POS_API_KEY_HEADER', 'api-key'),
            'guzzle' => [],
        ],
    ],
];
```

## Authentication / usage

Like Socialite, pick a driver by name and call its methods:

```php
use Tims\Foodlite\Facades\Foodlite;

// Restaurant discovery
$results = Foodlite::driver('zomato')->search([
    'q' => 'pizza',
    'lat' => 28.6139,
    'lon' => 77.2090,
    'count' => 10,
]);

$restaurant = Foodlite::driver('zomato')->restaurant(16774318);

// POS — menu, orders, outlets
Foodlite::driver('zomato-pos')->confirmOrder([
    'order_id' => 99,
    'restaurant_id' => 123,
]);

Foodlite::driver('zomato-pos')->updateDeliveryStatus([
    'restaurant_id' => 123,
    'delivery_status' => true,
]);
```

### Multiple drivers in one request

Configure both providers, then use whichever you need — same as using GitHub and Google with Socialite:

```php
use Tims\Foodlite\Facades\Foodlite;

$discovery = Foodlite::driver('zomato');
$pos = Foodlite::driver('zomato-pos');

$discovery->categories();
$pos->getDeliveryStatus(['restaurant_id' => 123]);
```

### Dynamic driver selection

```php
public function search(string $provider)
{
    abort_unless(in_array($provider, ['zomato', 'zomato-pos'], true), 404);

    return Foodlite::driver($provider)->client();
}
```

### Default driver

When you omit the name, the configured default is used:

```php
Foodlite::driver()->categories(); // uses FOODLITE_DRIVER / config default
```

### Underlying SDK clients

```php
$zomatoClient = Foodlite::driver('zomato')->client();      // Tims\Zomato\ZomatoClient
$posClient = Foodlite::driver('zomato-pos')->client();     // Tims\ZomatoPos\PosClient
```

## Driver methods

### `zomato`

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

### `zomato-pos`

| Area | Methods |
|------|---------|
| Menu | `addMenu`, `getMenu`, `updateItemStock` |
| Orders | `confirmOrder`, `rejectOrder`, `markOrderReady`, `markOrderPickedUp`, `markOrderAssigned`, `markOrderDelivered`, `getOrdersRating`, `updateComplaint`, `updateMerchantAgreedCancellation`, `getOrderContactDetails` |
| Outlets | `updateDeliveryCharge`, `getDeliveryStatus`, `updateDeliveryStatus`, `getDeliveryTime`, `addSurgeDeliveryTime`, `getZomatoDeliveryTimings`, `updateZomatoDeliveryTimings`, `getSelfDeliveryTimings`, `updateSelfDeliveryTimings`, `getLogisticsStatus`, `updateSelfDeliveryServiceability` |
| SDK | `client()` → raw `PosClient` |

POS payload shapes follow the partner [Zomato POS API reference](https://www.zomato.com/developer/integration/api-reference/v1/endpoints/).

## Custom drivers

Same extension pattern as Socialite community providers:

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
tims/zomato-php-sdk       → Restaurant API SDK (Packagist)
tims/zomato-pos-php-sdk   → POS Integration SDK (Packagist)
tims/foodlite             → Laravel manager + drivers (Socialite-style)
```

## Testing

```bash
composer test
```

## License

MIT
