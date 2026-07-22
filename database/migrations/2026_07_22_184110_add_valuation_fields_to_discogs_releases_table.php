<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('discogs_releases', function (Blueprint $table) {

            /*
             * Bron van de waardering
             *
             * Voorbeelden:
             * - discogs_median
             * - discogs_lowest_fallback
             */
            $table->string('valuation_source')
                ->nullable()
                ->after('currency');


            /*
             * Betrouwbaarheid van de waardering
             *
             * Voorbeelden:
             * - HIGH
             * - MEDIUM
             * - LOW
             */
            $table->string('valuation_confidence')
                ->nullable()
                ->after('valuation_source');

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discogs_releases', function (Blueprint $table) {

            $table->dropColumn([
                'valuation_source',
                'valuation_confidence',
            ]);

        });
    }
};