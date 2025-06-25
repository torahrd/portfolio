<?php

return [
  /*
    |--------------------------------------------------------------------------
    | Google API Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for Google API services.
    |
    */

  'api_key' => env('GOOGLE_MAPS_API_KEY'),

  /*
    |--------------------------------------------------------------------------
    | Google Places API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Google Places API service.
    |
    */

  'places' => [
    'api_key' => env('GOOGLE_MAPS_API_KEY'),
    'language' => env('GOOGLE_PLACES_LANGUAGE', 'ja'),
    'region' => env('GOOGLE_PLACES_REGION', 'JP'),
  ],

  /*
    |--------------------------------------------------------------------------
    | Google Maps API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Google Maps API service.
    |
    */

  'maps' => [
    'api_key' => env('GOOGLE_MAPS_API_KEY'),
    'map_id' => env('GOOGLE_MAPS_MAP_ID'),
  ],

  /*
    |--------------------------------------------------------------------------
    | Google Geocoding API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Google Geocoding API service.
    |
    */

  'geocoding' => [
    'api_key' => env('GOOGLE_MAPS_API_KEY'),
    'language' => env('GOOGLE_GEOCODING_LANGUAGE', 'ja'),
    'region' => env('GOOGLE_GEOCODING_REGION', 'JP'),
  ],
];
