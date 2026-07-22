<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ListingImportController;


Route::post(
    '/listings/import',
    [ListingImportController::class, 'store']
);