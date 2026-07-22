<?php

namespace App\Services\Discogs;

use App\Models\Listing;
use App\Models\DiscogsRelease;

class DiscogsMatcher
{
    public function __construct(
        private DiscogsService $discogs
    ) {
    }


    /**
     * Probeer een listing te koppelen aan een Discogs release.
     */
    public function match(Listing $listing): ?DiscogsRelease
    {
        $release = $this->discogs->findBestMatch(
            $listing->artist,
            $listing->title
        );


        if ($release === null) {

            $listing->update([
                'match_status' => 'unmatched',
                'match_confidence' => 'LOW',
            ]);

            return null;
        }


        $listing->update([
            'discogs_release_id' => $release->id,
            'match_status' => 'matched',
            'match_confidence' => 'HIGH',
        ]);


        return $release;
    }
}