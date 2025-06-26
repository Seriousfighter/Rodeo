<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRodeoRequest;
use App\Http\Requests\UpdateRodeoRequest;
use App\Models\Rodeo;
use App\Services\Interfaces\RodeoInterface;
use Inertia\Inertia;

class RodeoController extends Controller
{

    protected RodeoInterface $rodeoService;
    public function __construct(RodeoInterface $rodeoService)
    {
        $this->rodeoService = $rodeoService;
    }    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRodeoRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Rodeo $rodeo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rodeo $rodeo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRodeoRequest $request, Rodeo $rodeo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rodeo $rodeo)
    {
        //
    }
}
