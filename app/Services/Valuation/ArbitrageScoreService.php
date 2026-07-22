<?php

namespace App\Services\Valuation;

class ArbitrageScoreService
{
    /**
     * Bereken arbitrage score.
     */
    public function calculate(
        float $listingPrice,
        ?float $baselineValue
    ): array {

        if ($baselineValue === null || $listingPrice <= 0) {

            return [
                'score' => null,
                'rating' => 'UNKNOWN',
            ];
        }


        $margin =
            $baselineValue - $listingPrice;


        $percentage =
            ($margin / $listingPrice) * 100;


        return [
            'score' => round($percentage, 2),
            'rating' => $this->rating($percentage),
        ];
    }


    /**
     * Classificatie van de kans.
     */
    private function rating(
        float $percentage
    ): string {

        return match (true) {

            $percentage < 0 =>
                'LOSS',

            $percentage < 50 =>
                'LOW',

            $percentage < 150 =>
                'GOOD',

            default =>
                'EXCELLENT',
        };
    }
}