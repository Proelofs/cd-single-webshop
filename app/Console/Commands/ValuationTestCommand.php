<?php

namespace App\Console\Commands;

use App\Models\Listing;
use App\Services\Valuation\ValuationService;
use Illuminate\Console\Command;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Attributes\Description;

#[Signature('valuation:test')]
#[Description('Test CD waardering en arbitrage berekening')]
class ValuationTestCommand extends Command
{
    public function handle(
        ValuationService $valuationService
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
                    $valuation['value']
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
            ]
        );


        return self::SUCCESS;
    }
}