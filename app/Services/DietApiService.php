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

            Log::error("Diet API error: {$response->status()} - {$response->body()}");
            throw new \Exception("API request failed: {$response->status()}");

        } catch (\Exception $e) {
            Log::error("Diet API connection error: {$e->getMessage()}");
            throw $e;
        }
    }

    public function getAllDiets($filters = [])
    {
        $query = http_build_query(array_filter($filters));
        return $this->makeRequest('GET', '/diets' . ($query ? '?' . $query : ''));
    }

    public function getDiet($id)
    {
        return $this->makeRequest('GET', "/diets/{$id}");
    }

    public function createDiet($data)
    {
        return $this->makeRequest('POST', '/diets', $data);
    }

    public function updateDiet($id, $data)
    {
        return $this->makeRequest('PUT', "/diets/{$id}", $data);
    }

    public function deleteDiet($id)
    {
        return $this->makeRequest('DELETE', "/diets/{$id}");
    }

    public function getDietsByType($type)
    {
        return $this->makeRequest('GET', "/diets/type/{$type}");
    }

    public function addComponent($dietId, $componentData)
    {
        return $this->makeRequest('POST', "/diets/{$dietId}/components", $componentData);
    }

    public function updateComponent($dietId, $componentId, $componentData)
    {
        return $this->makeRequest('PUT', "/diets/{$dietId}/components/{$componentId}", $componentData);
    }

    public function removeComponent($dietId, $componentId)
    {
        return $this->makeRequest('DELETE', "/diets/{$dietId}/components/{$componentId}");
    }

    // Group Diet Methods
    public function getAllGroupDiets($filters = [])
    {
        $query = http_build_query(array_filter($filters));
        return $this->makeRequest('GET', '/group-diets' . ($query ? '?' . $query : ''));
    }

    public function getRodeoDiets($rodeoId)
    {
        //dd($rodeoId);
        return $this->makeRequest('GET', "/group-diets/rodeo/{$rodeoId}");
    }

    public function getGroupDietByGroupId($groupId)
    {
        return $this->makeRequest('GET', "/group-diets/group/{$groupId}");
    }

    public function assignDietToGroup($data)
    {
        // Asegurarse de que rodeo_id esté incluido
        if (!isset($data['rodeo_id'])) {
            throw new \Exception('rodeo_id is required');
        }
        return $this->makeRequest('POST', '/group-diets', $data);
    }

    public function updateGroupDiet($groupId, $dietId, $data)
    {
        return $this->makeRequest('PUT', "/group-diets/{$groupId}/diets/{$dietId}", $data);
    }

    public function removeGroupDiet($groupId, $dietId)
    {
        return $this->makeRequest('DELETE', "/group-diets/{$groupId}/diets/{$dietId}");
    }

    public function setActiveGroupDiet($groupId, $dietId)
    {
        return $this->makeRequest('POST', "/group-diets/{$groupId}/activate/{$dietId}");
    }

    public function getActiveGroupDiet($groupId)
    {
        return $this->makeRequest('GET', "/group-diets/{$groupId}/active");
    }
    public function healthCheck()
    {
        return $this->makeRequest('GET', '/health');
    }
}