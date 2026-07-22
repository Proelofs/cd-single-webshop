<?php

namespace App\Services\Discogs;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class DiscogsClient
{
    private PendingRequest $client;

    public function __construct()
    {
        $token = config('discogs.token');

        if (blank($token)) {
            throw new RuntimeException(
                'DISCOGS_TOKEN ontbreekt in het .env-bestand.'
            );
        }

        $this->client = Http::baseUrl(config('discogs.base_url'))
            ->acceptJson()
            ->withToken($token, 'Discogs')
            ->withUserAgent(config('discogs.user_agent'))
            ->timeout(config('discogs.timeout', 10))
            ->retry(3, 1000);
    }

    /**
     * Zoek releases op artiest en titel.
     */
    public function search(string $artist, string $title): array
    {
        return $this->get('/database/search', [
            'artist'        => $artist,
            'release_title' => $title,
            'type'          => 'release',
        ]);
    }

    /**
     * Haal een release op.
     */
    public function getRelease(int $releaseId): array
    {
        return $this->get("/releases/{$releaseId}");
    }

    /**
     * Haal Marketplace statistieken op.
     */
    public function getMarketplaceStats(int $releaseId): array
    {
        return $this->get("/marketplace/stats/{$releaseId}");
    }

    /**
     * Algemene GET helper.
     */
    private function get(string $uri, array $query = []): array
    {
        try {

            $response = $this->client
                ->get($uri, $query)
                ->throw();

            return $response->json();

        } catch (RequestException $e) {

            throw new RuntimeException(
                sprintf(
                    'Discogs API request mislukt (%s): %s',
                    $uri,
                    $e->getMessage()
                ),
                previous: $e
            );
        }
    }
}