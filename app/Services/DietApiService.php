<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DietApiService
{
    private $baseUrl;
    private $apiKey;
    private $timeout;

    public function __construct()
    {
        $this->baseUrl = env('DIET_API_URL', 'http://diet-api:3001');
        $this->apiKey = env('DIET_API_SECRET', 'tu_clave_secreta_aqui');
        $this->timeout = 30;
    }
}