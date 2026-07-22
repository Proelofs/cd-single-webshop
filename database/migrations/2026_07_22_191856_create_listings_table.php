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
        Schema::create('listings', function (Blueprint $table) {

            $table->id();


            /*
             * Bron van de aanbieding
             */
            $table->string('source');


            /*
             * ID bij externe bron
             */
            $table->string('external_id')
                ->nullable();


            /*
             * Originele advertentie URL
             */
            $table->text('url')
                ->nullable();


            /*
             * CD informatie
             */
            $table->string('artist')
                ->nullable();

            $table->string('title')
                ->nullable();

            $table->string('format')
                ->nullable();

            $table->string('condition')
                ->nullable();


            /*
             * Prijs
             */
            $table->decimal('price', 10, 2);

            $table->string('currency')
                ->default('EUR');


            /*
             * Koppeling Discogs
             */
            $table->foreignId('discogs_release_id')
                ->nullable()
                ->constrained('discogs_releases')
                ->nullOnDelete();


            /*
             * Status verwerking
             */
            $table->string('status')
                ->default('new');


            $table->timestamps();


            $table->index([
                'source',
                'external_id',
            ]);

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};