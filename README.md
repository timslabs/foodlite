# Foodlite

Laravel package for multi-provider food API integrations with a unified driver interface.

Built-in drivers:

| Driver | Package | Purpose |
|--------|---------|---------|
| `zomato` | [`tims/zomato-php-sdk`](https://packagist.org/packages/tims/zomato-php-sdk) | Restaurant API v2.1 (discovery / search) |
| `zomato_pos` | [`tims/zomato-pos-php-sdk`](https://packagist.org/packages/tims/zomato-pos-php-sdk) | POS Integration (menu, orders, outlets) |

Multiple drivers can be used in the same request. Custom drivers can be registered with `Foodlite::extend()`.

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

```env
FOODLITE_DRIVER=zomato

# Restaurant API (zomato)
ZOMATO_USER_KEY=your-user-key
# ZOMATO_BASE_URL=https://developers.zomato.com/api/v2.1

# POS Integration API (zomato_pos)
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
        'zomato_pos' => [
            'api_key' => env('ZOMATO_POS_API_KEY'),
            'base_url' => env('ZOMATO_POS_BASE_URL'),
            'api_key_header' => env('ZOMATO_POS_API_KEY_HEADER', 'api-key'),
            'guzzle' => [],
        ],
    ],
];
```

## Usage

### Single driver (default)

```php
use Tims\Foodlite\Facades\Foodlite;

$categories = Foodlite::driver()->categories();
```

### Multiple drivers at once

```php
use Tims\Foodlite\Facades\Foodlite;

$discovery = Foodlite::zomato();       // or Foodlite::driver('zomato')
$pos = Foodlite::zomatoPos();          // or Foodlite::driver('zomato_pos')

$results = $discovery->search([
    'q' => 'pizza',
    'lat' => 28.6139,
    'lon' => 77.2090,
    'count' => 10,
]);

$pos->confirmOrder([
    'order_id' => 99,
    'restaurant_id' => 123,
]);

$pos->updateDeliveryStatus([
    'restaurant_id' => 123,
    'delivery_status' => true,
]);
```

### Underlying SDK clients

```php
$zomatoClient = Foodlite::zomato()->client();      // Tims\Zomato\ZomatoClient
$posClient = Foodlite::zomatoPos()->client();      // Tims\ZomatoPos\PosClient
```

### Zomato driver methods (`zomato`)

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

### Zomato POS driver methods (`zomato_pos`)

| Area | Methods |
|------|---------|
| Menu | `addMenu`, `getMenu`, `updateItemStock` |
| Orders | `confirmOrder`, `rejectOrder`, `markOrderReady`, `markOrderPickedUp`, `markOrderAssigned`, `markOrderDelivered`, `getOrdersRating`, `updateComplaint`, `updateMerchantAgreedCancellation`, `getOrderContactDetails` |
| Outlets | `updateDeliveryCharge`, `getDeliveryStatus`, `updateDeliveryStatus`, `getDeliveryTime`, `addSurgeDeliveryTime`, `getZomatoDeliveryTimings`, `updateZomatoDeliveryTimings`, `getSelfDeliveryTimings`, `updateSelfDeliveryTimings`, `getLogisticsStatus`, `updateSelfDeliveryServiceability` |
| SDK | `client()` → raw `PosClient` |

Payload shapes follow the partner [Zomato POS API reference](https://www.zomato.com/developer/integration/api-reference/v1/endpoints/).

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
tims/zomato-php-sdk       → Restaurant API SDK (Packagist)
tims/zomato-pos-php-sdk   → POS Integration SDK (Packagist)
tims/foodlite             → Laravel manager + drivers (wraps both)
```

Plain PHP apps can use the SDKs directly. Laravel apps use Foodlite.

## Testing

```bash
composer test
```

## License

MIT
