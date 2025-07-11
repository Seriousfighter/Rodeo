<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnimalRequest;
use App\Http\Requests\UpdateAnimalRequest;
use App\Models\Animal;
use App\Services\Interfaces\AnimalInterface;
use App\Services\Interfaces\RodeoInterface;
use Inertia\Inertia;

class AnimalController extends Controller
{
    protected AnimalInterface $animalService;
    protected RodeoInterface $rodeoService;

    public function __construct(AnimalInterface $animalService, RodeoInterface $rodeoService)
    {
        $this->animalService = $animalService;
        $this->rodeoService = $rodeoService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $animals = $this->animalService->index();
            $rodeos = $this->rodeoService->index();
            return Inertia::render('Animals/Index', [
                'animals' => $animals,
                'rodeos' => $rodeos,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Error al cargar los animales: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage via AJAX.
     */
    public function store(StoreAnimalRequest $request)
    {
        try {
            $animal = $this->animalService->store($request->validated());
            $animalWithRelations = $this->animalService->show($animal->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Animal creado correctamente.',
                'animal' => $animalWithRelations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el animal: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Update the specified resource in storage via AJAX.
     */
    public function update(UpdateAnimalRequest $request, Animal $animal)
    {
        try {
            $updatedAnimal = $this->animalService->update($animal->id, $request->validated());
            $animalWithRelations = $this->animalService->show($animal->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Animal actualizado correctamente.',
                'animal' => $animalWithRelations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el animal: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage via AJAX.
     */
    public function destroy(Animal $animal)
    {
        try {
            $this->animalService->delete($animal->id);
            return response()->json([
                'success' => true,
                'message' => 'Animal eliminado correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el animal: ' . $e->getMessage()
            ], 422);
        }
    }
    


    // Mantener los métodos originales para compatibilidad
    public function create()
    {
        try {
            $rodeos = $this->rodeoService->index();
            return Inertia::render('Animals/Save', [
                'rodeos' => $rodeos,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('animals.index')->withErrors([
                'error' => 'Error al cargar el formulario de creación: ' . $e->getMessage()
            ]);
        }
    }

    public function show(Animal $animal)
    {
        try {
            $animalData = $this->animalService->show($animal->id);
            return Inertia::render('Animals/Show', [
                'animal' => $animalData,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('animals.index')->withErrors([
                'error' => 'Error al cargar el animal: ' . $e->getMessage()
            ]);
        }
    }

    public function edit(Animal $animal)
    {
        try {
            $animalData = $this->animalService->show($animal->id);
            $rodeos = $this->rodeoService->index();
            return Inertia::render('Animals/Save', [
                'animal' => $animalData,
                'rodeos' => $rodeos,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('animals.index')->withErrors([
                'error' => 'Error al cargar el formulario de edición: ' . $e->getMessage()
            ]);
        }
    }
}