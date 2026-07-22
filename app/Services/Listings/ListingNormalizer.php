<?php

namespace App\Services\Listings;

use App\Models\Listing;

class ListingNormalizer
{
    /**
     * Normaliseer marketplace data.
     */
    public function normalize(Listing $listing): Listing
    {
        $title = $listing->marketplace_title
            ?? $listing->title;


        $artist = $listing->artist;

        $normalizedTitle = $title;


        /*
         * Eerste eenvoudige versie:
         *
         * - behoud bestaande artiest indien aanwezig
         * - verwijder algemene CD termen
         *
         * Later vervangen we dit door
         * slimmere matching/NLP.
         */

        $normalizedTitle = preg_replace(
            '/\b(cd|album|compact disc)\b/i',
            '',
            $normalizedTitle
        );


        $normalizedTitle = trim(
            preg_replace('/\s+/', ' ', $normalizedTitle)
        );


        /*
         * Als artiest ontbreekt:
         * proberen we eerste woord als artiest te gebruiken.
         *
         * Voorbeeld:
         * Madonna Ray Of Light
         *
         * wordt:
         * artist = Madonna
         * title  = Ray Of Light
         */

        if (!$artist && str_contains($normalizedTitle, ' ')) {

            $parts = explode(
                ' ',
                $normalizedTitle,
                2
            );

            $artist = $parts[0];
            $normalizedTitle = $parts[1];

        }


        $listing->update([

            'normalized_artist' => $artist,

            'normalized_title' => $normalizedTitle,

            'marketplace_title' => $title,

        ]);


        return $listing;
    }
}