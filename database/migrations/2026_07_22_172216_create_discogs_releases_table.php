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
        Schema::create('discogs_releases', function (Blueprint $table) {

            $table->id();
    
            // Discogs gegevens
            $table->unsignedBigInteger('discogs_id')->unique();
    
            $table->string('artist');
            $table->string('title');
    
            $table->string('country')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
    
            $table->string('label')->nullable();
            $table->string('catalog_number')->nullable();
            $table->string('barcode')->nullable();
    
            // Marketplace waardes
            $table->decimal('lowest_price', 10, 2)->nullable();
            $table->decimal('median_price', 10, 2)->nullable();
            $table->decimal('highest_price', 10, 2)->nullable();
    
            $table->char('currency', 3)->default('EUR');
    
            // Discogs links
            $table->string('thumb')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('resource_url')->nullable();
    
            // Synchronisatie
            $table->timestamp('last_synced_at')->nullable();
    
            $table->timestamps();
    
            // Indexen
            $table->index('artist');
            $table->index('title');
            $table->index('catalog_number');
            $table->index('barcode');
            $table->index(['artist', 'title']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discogs_releases');
    }
};
