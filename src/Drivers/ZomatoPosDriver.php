<?php

declare(strict_types=1);

namespace Tims\Foodlite\Drivers;

use GuzzleHttp\ClientInterface;
use Tims\ZomatoPos\Http\HttpClient;
use Tims\ZomatoPos\PosClient;

/**
 * Laravel driver wrapping tims/zomato-pos-php-sdk.
 */
class ZomatoPosDriver extends AbstractDriver
{
    private ?PosClient $client = null;

    public function getName(): string
    {
        return 'zomato_pos';
    }

    public function client(?ClientInterface $httpClient = null): PosClient
    {
        if ($httpClient !== null) {
            return $this->makeClient($httpClient);
        }

        return $this->client ??= $this->makeClient();
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function addMenu(array $payload): array
    {
        return $this->client()->addMenu($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function getMenu(array $payload): array
    {
        return $this->client()->getMenu($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function updateItemStock(array $payload): array
    {
        return $this->client()->updateItemStock($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function confirmOrder(array $payload): array
    {
        return $this->client()->confirmOrder($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function rejectOrder(array $payload): array
    {
        return $this->client()->rejectOrder($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function markOrderReady(array $payload): array
    {
        return $this->client()->markOrderReady($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function markOrderPickedUp(array $payload): array
    {
        return $this->client()->markOrderPickedUp($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function markOrderAssigned(array $payload): array
    {
        return $this->client()->markOrderAssigned($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function markOrderDelivered(array $payload): array
    {
        return $this->client()->markOrderDelivered($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function getOrdersRating(array $payload): array
    {
        return $this->client()->getOrdersRating($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function updateComplaint(array $payload): array
    {
        return $this->client()->updateComplaint($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function updateMerchantAgreedCancellation(array $payload): array
    {
        return $this->client()->updateMerchantAgreedCancellation($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function getOrderContactDetails(array $payload): array
    {
        return $this->client()->getOrderContactDetails($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function updateDeliveryCharge(array $payload): array
    {
        return $this->client()->updateDeliveryCharge($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function getDeliveryStatus(array $payload): array
    {
        return $this->client()->getDeliveryStatus($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function updateDeliveryStatus(array $payload): array
    {
        return $this->client()->updateDeliveryStatus($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function getDeliveryTime(array $payload): array
    {
        return $this->client()->getDeliveryTime($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function addSurgeDeliveryTime(array $payload): array
    {
        return $this->client()->addSurgeDeliveryTime($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function getZomatoDeliveryTimings(array $payload): array
    {
        return $this->client()->getZomatoDeliveryTimings($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function updateZomatoDeliveryTimings(array $payload): array
    {
        return $this->client()->updateZomatoDeliveryTimings($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function getSelfDeliveryTimings(array $payload): array
    {
        return $this->client()->getSelfDeliveryTimings($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function updateSelfDeliveryTimings(array $payload): array
    {
        return $this->client()->updateSelfDeliveryTimings($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function getLogisticsStatus(array $payload): array
    {
        return $this->client()->getLogisticsStatus($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function updateSelfDeliveryServiceability(array $payload): array
    {
        return $this->client()->updateSelfDeliveryServiceability($payload);
    }

    private function makeClient(?ClientInterface $httpClient = null): PosClient
    {
        $apiKey = (string) ($this->config['api_key'] ?? '');
        $baseUrl = $this->config['base_url'] ?? null;
        $apiKeyHeader = $this->config['api_key_header'] ?? null;
        /** @var array<string, mixed> $guzzle */
        $guzzle = is_array($this->config['guzzle'] ?? null) ? $this->config['guzzle'] : [];

        return new PosClient(
            apiKey: $apiKey,
            httpClient: $httpClient,
            guzzleOptions: $guzzle,
            baseUrl: is_string($baseUrl) && $baseUrl !== ''
                ? $baseUrl
                : HttpClient::BASE_URL,
            apiKeyHeader: is_string($apiKeyHeader) && $apiKeyHeader !== ''
                ? $apiKeyHeader
                : HttpClient::DEFAULT_API_KEY_HEADER,
        );
    }
}
