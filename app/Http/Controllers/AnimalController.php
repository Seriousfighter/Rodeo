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
            return Inertia::render('Animals/Index', [
                'animals' => $animals,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Error al cargar los animales: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnimalRequest $request)
    {
        try {
            $animal = $this->animalService->store($request->validated());
            return redirect()->route('animals.index')->with('success', 'Animal creado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Error al crear el animal: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
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

    /**
     * Show the form for editing the specified resource.
     */
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

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnimalRequest $request, Animal $animal)
    {
        try {
            $updatedAnimal = $this->animalService->update($animal->id, $request->validated());
            return redirect()->route('animals.index')->with('success', 'Animal actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Error al actualizar el animal: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Animal $animal)
    {
        try {
            $this->animalService->delete($animal->id);
            return redirect()->route('animals.index')->with('success', 'Animal eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Error al eliminar el animal: ' . $e->getMessage()
            ]);
        }
    }
}