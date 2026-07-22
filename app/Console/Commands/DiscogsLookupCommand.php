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
    public function handle(): int
    {
        $artist = $this->argument('artist');
        $title = $this->argument('title');

        /** @var DiscogsService $discogs */
        $discogs = app(DiscogsService::class);

        $this->info('Zoeken op Discogs...');
        $this->newLine();

        $match = $discogs->findBestMatch($artist, $title);

        if ($match === null) {
            $this->error('Geen release gevonden.');

            return self::FAILURE;
        }

        $this->table(
            ['Veld', 'Waarde'],
            [
                ['Discogs ID', $match['id'] ?? '-'],
                ['Titel', $match['title'] ?? '-'],
                ['Jaar', $match['year'] ?? '-'],
                ['Land', $match['country'] ?? '-'],
                ['Type', $match['type'] ?? '-'],
            ]
        );

        return self::SUCCESS;
    }
}