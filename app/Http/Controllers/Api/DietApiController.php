<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Services\DietApiService;
use App\Services\Interfaces\AnimalInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DietApiController extends Controller{
    private $dietApiService;

    public function __construct(DietApiService $dietApiService)
    {
        $this->dietApiService = $dietApiService;
    }

    public function index(Request $request): JsonResponse
    {
        $rodeo_id = 1;
        
        $message = $this->dietApiService->getRodeoDiets($rodeo_id);
        //$message = 'Diet API is working!';
        return response()->json(['message' => $message]);
    }
}