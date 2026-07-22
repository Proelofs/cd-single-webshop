<?php

namespace App\Services\Discogs;

use Illuminate\Support\Facades\Http;

class DiscogsClient
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('discogs.base_url');
    }


    /**
     * Zoek releases via Discogs database search.
     */
    public function search(
        string $artist,
        string $title
    ): array {
        return Http::acceptJson()
            ->withHeaders([
                'Authorization' => 'Discogs token=' . config('discogs.token'),
                'User-Agent' => config('discogs.user_agent'),
            ])
            ->get(
                $this->baseUrl . '/database/search',
                [
                    'artist' => $artist,
                    'release_title' => $title,
                    'type' => 'release',
                ]
            )
            ->json();
    }


    /**
     * Haal volledige release informatie op.
     */
    public function release(int $id): array
    {
        return Http::acceptJson()
            ->withHeaders([
                'Authorization' => 'Discogs token=' . config('discogs.token'),
                'User-Agent' => config('discogs.user_agent'),
            ])
            ->get(
                $this->baseUrl . '/releases/' . $id
            )
            ->json();
    }


    /**
     * Haal marketplace statistieken op.
     */
    public function marketplaceStats(int $id): array
    {
        return Http::acceptJson()
            ->withHeaders([
                'Authorization' => 'Discogs token=' . config('discogs.token'),
                'User-Agent' => config('discogs.user_agent'),
            ])
            ->get(
                $this->baseUrl . '/marketplace/stats/' . $id
            )
            ->json();
    }
}