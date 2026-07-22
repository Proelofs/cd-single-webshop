<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Listing extends Model
{
    protected $fillable = [

        'source',
        'external_id',
        'url',
    
        'artist',
        'title',
        'marketplace_title',
        'normalized_artist',
        'normalized_title',
    
        'format',
        'condition',
    
        'price',
        'currency',
    
        'discogs_release_id',
    
        'match_status',
        'match_confidence',
    
        'status',

    ];


    protected $casts = [

        'price' => 'decimal:2',

    ];


    /**
     * Koppeling naar Discogs release.
     */
    public function discogsRelease(): BelongsTo
    {
        return $this->belongsTo(
            DiscogsRelease::class
        );
    }
}