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

            $table->string('match_status')
                ->default('pending')
                ->after('discogs_release_id');

            $table->string('match_confidence')
                ->nullable()
                ->after('match_status');

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {

            $table->dropColumn([
                'match_status',
                'match_confidence',
            ]);

        });
    }
};