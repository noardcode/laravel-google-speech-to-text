<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Google Service Account
     |--------------------------------------------------------------------------
     */
    'service-account' => __DIR__ . '/../service-account.json',
    
    /*
     |--------------------------------------------------------------------------
     | Default parameters injected by the Service Provider
     |--------------------------------------------------------------------------
     */
    'defaults' => [
        'language' => 'en-US',
        'encoding' => \Google\Cloud\Speech\V1\RecognitionConfig\AudioEncoding::LINEAR16,
        'sampleRateHertz' => 44100
    ]
];
