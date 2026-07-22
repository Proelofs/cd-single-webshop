<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscogsRelease extends Model
{
    protected $fillable = [
        'discogs_id',

        'artist',
        'title',

        'country',
        'year',

        'label',
        'catalog_number',
        'barcode',

        'thumb',
        'cover_image',
        'resource_url',

        'lowest_price',
        'median_price',
        'highest_price',

        'currency',

        'valuation_source',
        'valuation_confidence',

        'last_synced_at',
    ];


    protected $casts = [
        'year' => 'integer',

        'lowest_price' => 'decimal:2',
        'median_price' => 'decimal:2',
        'highest_price' => 'decimal:2',

        'last_synced_at' => 'datetime',
    ];
}