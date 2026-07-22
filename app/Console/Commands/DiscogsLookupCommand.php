<?php

namespace App\Console\Commands;

use App\Services\Discogs\DiscogsService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('discogs:lookup {artist} {title}')]
#[Description('Zoek een release op Discogs')]
class DiscogsLookupCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(
        DiscogsService $discogs
    ): int {
        $artist = $this->argument('artist');
        $title = $this->argument('title');

        $this->info('Zoeken op Discogs...');
        $this->newLine();

        $release = $discogs->findBestMatch(
            $artist,
            $title
        );

        if ($release === null) {
            $this->error('Geen release gevonden.');

            return self::FAILURE;
        }

        $this->table(
            ['Veld', 'Waarde'],
            [
                [
                    'Discogs ID',
                    $release->discogs_id
                ],
                [
                    'Titel',
                    $release->artist . ' - ' . $release->title
                ],
                [
                    'Jaar',
                    $release->year ?? '-'
                ],
                [
                    'Land',
                    $release->country ?? '-'
                ],
                [
                    'Baseline (median)',
                    $release->median_price
                        ? $release->median_price . ' ' . $release->currency
                        : '-'
                ],
            ]
        );

        return self::SUCCESS;
    }
}