<?php

return [
    'enabled' => env('CSP_ENABLED', true),
    'policy' => \App\Policies\GoogleMapsPolicy::class,
    'report_only' => env('CSP_REPORT_ONLY', false),
    'report_uri' => env('CSP_REPORT_URI', ''),
]; 