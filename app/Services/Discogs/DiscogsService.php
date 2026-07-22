<?php

namespace App\Services\Discogs;

class DiscogsService
{
    public function __construct(
        private readonly DiscogsClient $client
    ) {
    }

    /**
     * Zoek releases op artiest en titel.
     */
    public function search(string $artist, string $title): array
    {
        return $this->client->search($artist, $title);
    }

    /**
     * Haal één release op.
     */
    public function getRelease(int $releaseId): array
    {
        return $this->client->getRelease($releaseId);
    }

    /**
     * Haal marketplace statistieken op.
     */
    public function getMarketplaceStats(int $releaseId): array
    {
        return $this->client->getMarketplaceStats($releaseId);
    }

    /**
     * Zoek de beste match.
     *
     * Voorlopig nemen we het eerste resultaat.
     * Later voegen we fuzzy matching toe.
     */
    public function findBestMatch(string $artist, string $title): ?array
    {
        $results = $this->search($artist, $title);

        if (
            ! isset($results['results']) ||
            count($results['results']) === 0
        ) {
            return null;
        }

        return $results['results'][0];
    }
}