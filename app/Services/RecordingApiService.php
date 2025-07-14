<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecordingApiService
{
    private $baseUrl;
    private $apiKey;
    private $timeout;

    public function __construct()
    {
        $this->baseUrl = env('RECORDING_API_URL', 'http://recording-api:3000');
        $this->apiKey = env('RECORDING_API_SECRET', 'tu_clave_secreta_aqui');
        $this->timeout = 30;
    }

    private function makeRequest($method, $endpoint, $data = null)
    {
        try {
            $request = Http::timeout($this->timeout)
                ->withHeaders([
                    'X-API-Key' => $this->apiKey,
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'Laravel-RodeoApp/1.0'
                ]);

            $response = match(strtoupper($method)) {
                'GET' => $request->get($this->baseUrl . $endpoint),
                'POST' => $request->post($this->baseUrl . $endpoint, $data),
                'PUT' => $request->put($this->baseUrl . $endpoint, $data),
                'DELETE' => $request->delete($this->baseUrl . $endpoint),
                default => throw new \Exception("Unsupported HTTP method: {$method}")
            };

            if ($response->successful()) {
                return $response->json();
            }

            Log::error("Recording API error: {$response->status()} - {$response->body()}");
            throw new \Exception("API request failed: {$response->status()}");

        } catch (\Exception $e) {
            Log::error("Recording API connection error: {$e->getMessage()}");
            throw $e;
        }
    }

    public function index($filters = [])
    {
        $query = http_build_query($filters);
        return $this->makeRequest('GET', '/recordings' . ($query ? '?' . $query : ''));
    }

    public function show($id)
    {
        return $this->makeRequest('GET', "/recordings/{$id}");
    }

    public function store($data)
    {
        return $this->makeRequest('POST', '/recordings', $data);
    }

    public function update($id, $data)
    {
        return $this->makeRequest('PUT', "/recordings/{$id}", $data);
    }

    public function delete($id)
    {
        return $this->makeRequest('DELETE', "/recordings/{$id}");
    }

    public function getByAnimal($animalId)
    {
        return $this->makeRequest('GET', "/recordings/animal/{$animalId}");
    }

    public function getByRodeo($rodeoId)
    {
        return $this->makeRequest('GET', "/recordings/rodeo/{$rodeoId}");
    }

    public function getByClient($clientId)
    {
        return $this->makeRequest('GET', "/recordings/client/{$clientId}");
    }

    public function healthCheck()
    {
        return $this->makeRequest('GET', '/health');
    }
}