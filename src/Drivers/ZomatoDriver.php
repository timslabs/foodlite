<?php

declare(strict_types=1);

namespace Tims\Foodlite\Drivers;

use GuzzleHttp\ClientInterface;
use Tims\Zomato\Enums\EntityType;
use Tims\Zomato\Enums\Order;
use Tims\Zomato\Enums\Sort;
use Tims\Zomato\Http\HttpClient;
use Tims\Zomato\ZomatoClient;

/**
 * Laravel driver wrapping tims/zomato-php-sdk.
 */
class ZomatoDriver extends AbstractDriver
{
    private ?ZomatoClient $client = null;

    public function getName(): string
    {
        return 'zomato';
    }

    public function client(?ClientInterface $httpClient = null): ZomatoClient
    {
        if ($httpClient !== null) {
            return $this->makeClient($httpClient);
        }

        return $this->client ??= $this->makeClient();
    }

    /**
     * @return array<string, mixed>
     */
    public function categories(): array
    {
        return $this->client()->categories();
    }

    /**
     * @param  array{
     *     q?: string|null,
     *     lat?: float|null,
     *     lon?: float|null,
     *     city_ids?: string|list<int|string>|null,
     *     count?: int|null
     * }  $params
     * @return array<string, mixed>
     */
    public function cities(array $params = []): array
    {
        return $this->client()->cities($params);
    }

    /**
     * @param  array{
     *     city_id?: int|string|null,
     *     lat?: float|null,
     *     lon?: float|null,
     *     count?: int|null
     * }  $params
     * @return array<string, mixed>
     */
    public function collections(array $params = []): array
    {
        return $this->client()->collections($params);
    }

    /**
     * @param  array{
     *     city_id?: int|string|null,
     *     lat?: float|null,
     *     lon?: float|null
     * }  $params
     * @return array<string, mixed>
     */
    public function cuisines(array $params = []): array
    {
        return $this->client()->cuisines($params);
    }

    /**
     * @param  array{
     *     city_id?: int|string|null,
     *     lat?: float|null,
     *     lon?: float|null
     * }  $params
     * @return array<string, mixed>
     */
    public function establishments(array $params = []): array
    {
        return $this->client()->establishments($params);
    }

    /**
     * @return array<string, mixed>
     */
    public function geocode(float $lat, float $lon): array
    {
        return $this->client()->geocode($lat, $lon);
    }

    /**
     * @param  array{
     *     lat?: float|null,
     *     lon?: float|null,
     *     count?: int|null
     * }  $params
     * @return array<string, mixed>
     */
    public function locations(string $query, array $params = []): array
    {
        return $this->client()->locations($query, $params);
    }

    /**
     * @return array<string, mixed>
     */
    public function locationDetails(int|string $entityId, string|EntityType $entityType): array
    {
        return $this->client()->locationDetails($entityId, $entityType);
    }

    /**
     * @return array<string, mixed>
     */
    public function restaurant(int|string $resId): array
    {
        return $this->client()->restaurant($resId);
    }

    /**
     * @return array<string, mixed>
     */
    public function dailyMenu(int|string $resId): array
    {
        return $this->client()->dailyMenu($resId);
    }

    /**
     * @param  array{
     *     start?: int|null,
     *     count?: int|null
     * }  $params
     * @return array<string, mixed>
     */
    public function reviews(int|string $resId, array $params = []): array
    {
        return $this->client()->reviews($resId, $params);
    }

    /**
     * @param  array{
     *     entity_id?: int|string|null,
     *     entity_type?: string|EntityType|null,
     *     q?: string|null,
     *     start?: int|null,
     *     count?: int|null,
     *     lat?: float|null,
     *     lon?: float|null,
     *     radius?: float|null,
     *     cuisines?: string|list<int|string>|null,
     *     establishment_type?: int|string|null,
     *     collection_id?: int|string|null,
     *     category?: string|list<int|string>|null,
     *     sort?: string|Sort|null,
     *     order?: string|Order|null
     * }  $params
     * @return array<string, mixed>
     */
    public function search(array $params = []): array
    {
        return $this->client()->search($params);
    }

    private function makeClient(?ClientInterface $httpClient = null): ZomatoClient
    {
        $userKey = (string) ($this->config['user_key'] ?? '');
        $baseUrl = $this->config['base_url'] ?? null;
        /** @var array<string, mixed> $guzzle */
        $guzzle = is_array($this->config['guzzle'] ?? null) ? $this->config['guzzle'] : [];

        return new ZomatoClient(
            userKey: $userKey,
            httpClient: $httpClient,
            guzzleOptions: $guzzle,
            baseUrl: is_string($baseUrl) && $baseUrl !== ''
                ? $baseUrl
                : HttpClient::BASE_URL,
        );
    }
}
