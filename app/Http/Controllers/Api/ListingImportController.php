<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Listings\ListingImporter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListingImportController extends Controller
{
    public function store(
        Request $request,
        ListingImporter $importer
    ): JsonResponse {

        $data = $request->validate([

            'source' => [
                'required',
                'string',
            ],

            'external_id' => [
                'nullable',
                'string',
            ],

            'url' => [
                'nullable',
                'url',
            ],

            'artist' => [
                'nullable',
                'string',
            ],

            'title' => [
                'required',
                'string',
            ],

            'format' => [
                'nullable',
                'string',
            ],

            'condition' => [
                'nullable',
                'string',
            ],

            'price' => [
                'required',
                'numeric',
            ],

            'currency' => [
                'nullable',
                'string',
                'size:3',
            ],

        ]);


        $listing = $importer->import(
            $data
        );


        return response()->json([

            'success' => true,

            'listing' => $listing,

        ], 201);
    }
}