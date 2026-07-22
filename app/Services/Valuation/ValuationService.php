<?php

namespace App\Services\Valuation;

use App\Models\DiscogsRelease;

class ValuationService
{
    /**
     * Bepaal de Discogs baseline waarde.
     *
     * Discogs lowest_price is voorlopig
     * de gekozen baseline.
     */
    public function getBaselineValue(
        DiscogsRelease $release
    ): array {

        if ($release->lowest_price !== null) {

            return [
                'value' => $release->lowest_price,
                'currency' => $release->currency,
                'source' => 'discogs_lowest_price',
                'confidence' => 'LOW',
            ];
        }


        return [
            'value' => null,
            'currency' => $release->currency,
            'source' => 'none',
            'confidence' => 'NONE',
        ];
    }


    /**
     * Bereken verschil tussen vraagprijs en baseline.
     */
    public function calculateMargin(
        float $listingPrice,
        array $valuation
    ): array {

        if ($valuation['value'] === null) {

            return [
                'margin' => null,
                'percentage' => null,
            ];
        }


        $margin =
            $valuation['value']
            - $listingPrice;


        return [
            'margin' => round($margin, 2),

            'percentage' =>
                round(
                    ($margin / $listingPrice) * 100,
                    2
                ),
        ];
    }
}