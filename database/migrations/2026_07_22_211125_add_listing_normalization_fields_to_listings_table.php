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
        Schema::table('listings', function (Blueprint $table) {

            $table->string('marketplace_title')
                ->nullable()
                ->after('title');

            $table->string('normalized_artist')
                ->nullable()
                ->after('marketplace_title');

            $table->string('normalized_title')
                ->nullable()
                ->after('normalized_artist');

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {

            $table->dropColumn([
                'marketplace_title',
                'normalized_artist',
                'normalized_title',
            ]);

        });
    }
};