<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRodeoRequest;
use App\Http\Requests\UpdateRodeoRequest;
use App\Models\Rodeo;
use App\Services\Interfaces\RodeoInterface;
use App\Services\Interfaces\ClientInterface;
use Inertia\Inertia;

class RodeoController extends Controller
{
    protected RodeoInterface $rodeoService;
    protected ClientInterface $clientService;

    public function __construct(RodeoInterface $rodeoService, ClientInterface $clientService)
    {
        $this->rodeoService = $rodeoService;
        $this->clientService = $clientService;
    }    

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $rodeos = $this->rodeoService->index();
            return Inertia::render('Rodeos/Index', [
                'rodeos' => $rodeos,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Error al cargar los rodeos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $clients = $this->clientService->index();
            return Inertia::render('Rodeos/Save', [
                'clients' => $clients,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('rodeos.index')->withErrors([
                'error' => 'Error al cargar el formulario de creación: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRodeoRequest $request)
    {
        try {
            $rodeo = $this->rodeoService->store($request->validated());
            return redirect()->route('rodeos.index')->with('success', 'Rodeo creado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Error al crear el rodeo: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Rodeo $rodeo)
    {
        try {
            $rodeoData = $this->rodeoService->show($rodeo->id);
            return Inertia::render('Rodeos/Show', [
                'rodeo' => $rodeoData,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('rodeos.index')->withErrors([
                'error' => 'Error al cargar el rodeo: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rodeo $rodeo)
    {
        try {
            $rodeoData = $this->rodeoService->show($rodeo->id);
            $clients = $this->clientService->index();
            return Inertia::render('Rodeos/Save', [
                'rodeo' => $rodeoData,
                'clients' => $clients,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('rodeos.index')->withErrors([
                'error' => 'Error al cargar el formulario de edición: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRodeoRequest $request, Rodeo $rodeo)
    {
        try {
            $updatedRodeo = $this->rodeoService->update($rodeo->id, $request->validated());
            return redirect()->route('rodeos.index')->with('success', 'Rodeo actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Error al actualizar el rodeo: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rodeo $rodeo)
    {
        try {
            $this->rodeoService->delete($rodeo->id);
            return redirect()->route('rodeos.index')->with('success', 'Rodeo eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Error al eliminar el rodeo: ' . $e->getMessage()
            ]);
        }
    }
}