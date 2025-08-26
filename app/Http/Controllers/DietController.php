<?php

namespace App\Http\Controllers;

use App\Services\DietApiService;
use App\Services\Interfaces\AnimalInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Http\JsonResponse;

class DietController extends Controller
{
    private $dietApi;
    protected AnimalInterface $animalService;

    public function __construct(DietApiService $dietApi, AnimalInterface $animalService)
    {
        $this->dietApi = $dietApi;
        $this->animalService = $animalService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $filters = [
                'diet_type' => $request->get('diet_type'),
                'status' => $request->get('status', 'active'),
                'name' => $request->get('search'),
                'page' => $request->get('page', 1),
                'limit' => $request->get('limit', 12)
            ];

            $diets = $this->dietApi->getAllDiets($filters);
            
            return Inertia::render('Diet/Crud/Index', [
                'diets' => $diets,
                'filters' => $filters
            ]);
            
        } catch (\Exception $e) {
            
            $diets = $this->dietApi->getAllDiets($filters);
            dd($diets);
            return back()->withErrors([
                'error' => 'Error al obtener las dietas: ' . $e->getMessage()
            ]);

        }
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Diet/Crud/Save');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'components' => 'required|array|min:1',
            'components.*.name' => 'required|string|max:255',
            'components.*.amount' => 'required|string|max:50',
            'components.*.unit' => 'required|string|in:kg,gr,L,ml,units,other',
            'components.*.notes' => 'nullable|string',
            'total_weight' => 'nullable|string|max:50',
            'diet_type' => 'required|string|in:maintenance,growth,reproduction,lactation,custom',
            'notes' => 'nullable|string'
        ]);

        try {
            $dietData = [
                'name' => $request->name,
                'description' => $request->description ?? '',
                'components' => $request->components,
                'total_weight' => $request->total_weight ?? '',
                'diet_type' => $request->diet_type,
                'status' => 'active',
                //'created_by' => auth()->id(), hardcode por ahora
                'created_by' => 1,
                'notes' => $request->notes ?? ''
            ];

            $diet = $this->dietApi->createDiet($dietData);

            if ($diet['success']) {
                return redirect()->route('diets.index')
                    ->with('success', 'Dieta creada exitosamente');
            } else {
                return back()->withErrors([
                    'error' => $diet['error'] ?? 'Error al crear la dieta'
                ])->withInput();
            }
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Error al crear la dieta: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $diet = $this->dietApi->getDiet($id);

            if (!$diet['success']) {
                return redirect()->route('diets.index')
                    ->withErrors(['error' => 'Dieta no encontrada']);
            }

            return Inertia::render('Diet/Crud/Show', [
                'diet' => $diet['data']
            ]);
        } catch (\Exception $e) {
            return redirect()->route('diets.index')
                ->withErrors(['error' => 'Error al obtener la dieta: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $diet = $this->dietApi->getDiet($id);

            if (!$diet['success']) {
                return redirect()->route('diets.index')
                    ->withErrors(['error' => 'Dieta no encontrada']);
            }

            return Inertia::render('Diet/Crud/Save', [
                'diet' => $diet['data']
            ]);
        } catch (\Exception $e) {
            return redirect()->route('diets.index')
                ->withErrors(['error' => 'Error al obtener la dieta: ' . $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'components' => 'required|array|min:1',
            'components.*.name' => 'required|string|max:255',
            'components.*.amount' => 'required|string|max:50',
            'components.*.unit' => 'required|string|in:kg,gr,L,ml,units,other',
            'components.*.notes' => 'nullable|string',
            'total_weight' => 'nullable|string|max:50',
            'diet_type' => 'required|string|in:maintenance,growth,reproduction,lactation,custom',
            'notes' => 'nullable|string'
        ]);

        try {
            $dietData = [
                'name' => $request->name,
                'description' => $request->description ?? '',
                'components' => $request->components,
                'total_weight' => $request->total_weight ?? '',
                'diet_type' => $request->diet_type,
                'notes' => $request->notes ?? ''
            ];

            $diet = $this->dietApi->updateDiet($id, $dietData);

            if ($diet['success']) {
                return redirect()->route('diets.show', $id)
                    ->with('success', 'Dieta actualizada exitosamente');
            } else {
                return back()->withErrors([
                    'error' => $diet['error'] ?? 'Error al actualizar la dieta'
                ])->withInput();
            }
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Error al actualizar la dieta: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $result = $this->dietApi->deleteDiet($id);

            if ($result['success']) {
                return redirect()->route('diets.index')
                    ->with('success', 'Dieta eliminada exitosamente');
            } else {
                return back()->withErrors([
                    'error' => $result['error'] ?? 'Error al eliminar la dieta'
                ]);
            }
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Error al eliminar la dieta: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get diets by type
     */
    public function getByType(Request $request, string $type)
    {
        try {
            $diets = $this->dietApi->getDietsByType($type);
            return response()->json($diets);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al obtener dietas por tipo: ' . $e->getMessage()
            ], 500);
        }
    }
}