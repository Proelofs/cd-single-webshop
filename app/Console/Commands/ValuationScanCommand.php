<?php

namespace App\Console\Commands;

use App\Models\Listing;
use App\Services\Valuation\ArbitrageScoreService;
use App\Services\Valuation\ValuationService;
use Illuminate\Console\Command;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Attributes\Description;

#[Signature('valuation:scan')]
#[Description('Scan alle listings op arbitrage kansen')]
class ValuationScanCommand extends Command
{
    public function handle(
        ValuationService $valuationService,
        ArbitrageScoreService $scoreService
    ): int {

        $listings = Listing::with('discogsRelease')
            ->get();


        if ($listings->isEmpty()) {

            $this->error(
                'Geen listings gevonden.'
            );

            return self::FAILURE;
        }


        $results = [];


        foreach ($listings as $listing) {

            if ($listing->discogsRelease === null) {
                continue;
            }


            $valuation =
                $valuationService->getBaselineValue(
                    $listing->discogsRelease
                );


            $score =
                $scoreService->calculate(
                    (float) $listing->price,
                    $valuation['value']
                );


            $results[] = [

                'CD' =>
                    $listing->artist .
                    ' - ' .
                    $listing->title,

                'Markt' =>
                    ucfirst(
                        $listing->source
                    ),

                'Prijs' =>
                    number_format(
                        $listing->price,
                        2
                    )
                    . ' '
                    . $listing->currency,

                'Baseline' =>
                    $valuation['value'] !== null
                        ? number_format(
                            $valuation['value'],
                            2
                        )
                        . ' '
                        . $valuation['currency']
                        : '-',

                'Score' =>
                    $score['score'] !== null
                        ? $score['score'] . '%'
                        : '-',

                'Rating' =>
                    $score['rating'],
            ];
        }


        usort(
            $results,
            function ($a, $b) {

                return
                    (float) str_replace(
                        '%',
                        '',
                        $b['Score']
                    )
                    <=>
                    (float) str_replace(
                        '%',
                        '',
                        $a['Score']
                    );
            }
        );


        $this->table(
            [
                'CD',
                'Markt',
                'Prijs',
                'Baseline',
                'Score',
                'Rating',
            ],
            $results
        );


        return self::SUCCESS;
    }
}