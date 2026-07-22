<?php

namespace App\Services\Listings;

use App\Models\Listing;

class ListingImporter
{
    /**
     * Import een externe marketplace listing.
     */
    public function import(array $data): Listing
    {
        return Listing::create([
            'source' => $data['source'],
            'external_id' => $data['external_id'] ?? null,
            'url' => $data['url'] ?? null,

            'artist' => $data['artist'] ?? null,
            'title' => $data['title'],

            'format' => $data['format'] ?? 'CD',
            'condition' => $data['condition'] ?? null,

            'price' => $data['price'],
            'currency' => $data['currency'] ?? 'EUR',

            'status' => 'pending',
        ]);
    }
}