<?php

namespace App\Services\Discogs;

use App\Models\DiscogsRelease;

class DiscogsService
{
    public function __construct(
        private readonly DiscogsClient $client
    ) {
    }


    /**
     * Zoek release en synchroniseer met database.
     */
    public function findBestMatch(
        string $artist,
        string $title
    ): ?DiscogsRelease {

        $search = $this->client->search(
            $artist,
            $title
        );


        if (
            !isset($search['results']) ||
            count($search['results']) === 0
        ) {
            return null;
        }


        $result = $search['results'][0];

        $releaseId = (int) $result['id'];


        $release = $this->client->release(
            $releaseId
        );


        $stats = $this->client->marketplaceStats(
            $releaseId
        );


        $valuation = $this->determineValuation(
            $stats
        );


        return DiscogsRelease::updateOrCreate(
            [
                'discogs_id' => $releaseId,
            ],
            [
                'artist' => $this->artist($release),

                'title' => $release['title']
                    ?? $result['title'],

                'country' => $release['country']
                    ?? null,

                'year' => $release['year']
                    ?? null,


                'label' => $this->label($release),

                'catalog_number' => $this->catalogNumber($release),

                'barcode' => $this->barcode($release),


                'thumb' => $release['thumb']
                    ?? null,

                'cover_image' => $release['images'][0]['uri']
                    ?? null,

                'resource_url' => $release['resource_url']
                    ?? null,


                'lowest_price' =>
                    $stats['lowest_price']['value']
                    ?? null,


                'median_price' =>
                    $stats['median_price']['value']
                    ?? null,


                'highest_price' =>
                    $stats['highest_price']['value']
                    ?? null,


                'currency' =>
                    $stats['median_price']['currency']
                    ?? $stats['lowest_price']['currency']
                    ?? 'EUR',


                'valuation_source' =>
                    $valuation['source'],

                'valuation_confidence' =>
                    $valuation['confidence'],


                'last_synced_at' => now(),
            ]
        );
    }


    /**
     * Bepaal waarderingsbron.
     */
    private function determineValuation(array $stats): array
    {
        if (
            isset($stats['median_price']['value'])
        ) {
            return [
                'source' => 'discogs_median',
                'confidence' => 'HIGH',
            ];
        }


        if (
            isset($stats['lowest_price']['value'])
        ) {
            return [
                'source' => 'discogs_lowest_fallback',
                'confidence' => 'LOW',
            ];
        }


        return [
            'source' => null,
            'confidence' => null,
        ];
    }



    private function artist(array $release): string
    {
        return $release['artists'][0]['name']
            ?? '';
    }



    private function label(array $release): ?string
    {
        return $release['labels'][0]['name']
            ?? null;
    }



    private function catalogNumber(array $release): ?string
    {
        return $release['labels'][0]['catno']
            ?? null;
    }



    private function barcode(array $release): ?string
    {
        foreach (
            $release['identifiers'] ?? []
            as $identifier
        ) {
            if (
                ($identifier['type'] ?? null)
                === 'Barcode'
            ) {
                return $identifier['value'];
            }
        }

        return null;
    }
}