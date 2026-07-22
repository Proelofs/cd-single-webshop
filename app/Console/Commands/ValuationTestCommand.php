<?php

namespace App\Console\Commands;

use App\Models\Listing;
use App\Services\Valuation\ArbitrageScoreService;
use App\Services\Valuation\ValuationService;
use Illuminate\Console\Command;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Attributes\Description;

#[Signature('valuation:test')]
#[Description('Test CD waardering en arbitrage berekening')]
class ValuationTestCommand extends Command
{
    public function handle(
        ValuationService $valuationService,
        ArbitrageScoreService $scoreService
    ): int {

        $listing = Listing::latest()->first();


        if ($listing === null) {

            $this->error(
                'Geen listing gevonden.'
            );

            return self::FAILURE;
        }


        $release = $listing->discogsRelease;


        if ($release === null) {

            $this->error(
                'Geen Discogs release gekoppeld.'
            );

            return self::FAILURE;
        }


        $valuation =
            $valuationService->getBaselineValue(
                $release
            );


        $margin =
            $valuationService->calculateMargin(
                (float) $listing->price,
                $valuation
            );


        $score =
            $scoreService->calculate(
                (float) $listing->price,
                $valuation['value']
            );


        $this->table(
            [
                'Veld',
                'Waarde'
            ],
            [
                [
                    'CD',
                    $listing->artist .
                    ' - ' .
                    $listing->title
                ],
                [
                    'Vraagprijs',
                    $listing->price .
                    ' ' .
                    $listing->currency
                ],
                [
                    'Discogs waarde',
                    $valuation['value'] !== null
                        ? $valuation['value'] .
                          ' ' .
                          $valuation['currency']
                        : '-'
                ],
                [
                    'Bron',
                    $valuation['source']
                ],
                [
                    'Confidence',
                    $valuation['confidence']
                ],
                [
                    'Marge',
                    $margin['margin'] !== null
                        ? $margin['margin'] .
                          ' ' .
                          $listing->currency
                        : '-'
                ],
                [
                    'Percentage',
                    $margin['percentage'] !== null
                        ? $margin['percentage'] . '%'
                        : '-'
                ],
                [
                    'Score',
                    $score['score'] !== null
                        ? $score['score'] . '%'
                        : '-'
                ],
                [
                    'Rating',
                    $score['rating']
                ],
            ]
        );


        return self::SUCCESS;
    }
}