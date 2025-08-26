<?php

namespace App\Http\Controllers;

use App\Services\DietApiService;
use App\Services\Interfaces\AnimalInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DietController extends Controller
{
    private $dietApi;
    protected AnimalInterface $animalService;

    public function __construct(DietApiService $dietApi, AnimalInterface $animalService)
    {
        $this->dietApi = $dietApi;
        $this->animalService = $animalService;
    }
}