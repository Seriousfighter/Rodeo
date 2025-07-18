<?php


namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Services\Interfaces\GroupInterface;
use App\Services\Interfaces\RodeoInterface;
use Inertia\Inertia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GroupController extends Controller
{
    protected $groupService;
    protected RodeoInterface $rodeoService;
    public function __construct(RodeoInterface $rodeoService, GroupInterface $groupService)
    {
        $this->groupService = $groupService;
        $this->rodeoService = $rodeoService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(){}
    
    public function rodeoGroups(int $id)
    {
        
        try {

            $groups = $this->groupService->findByRodeoId($id);
            
            return inertia()->render('Groups/Index', [
                'groups' => $groups,
                'rodeoId' => $id
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Error al cargar los grupos del rodeo: ' . $e->getMessage()
            ]);
        }
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGroupRequest $request)
    {
        Log::info('Group store request data:', $request->validated());
        
        //dd($request->validated());
        try {
            $group = $this->groupService->store($request->validated());
            
            return redirect()->back()->with([
                'success' => true,
                'message' => 'Grupo creado exitosamente'
            ]);
        } catch (\Exception $e) {
           return redirect()->back()->withErrors([
                'error' => 'Error al crear el grupo: ' . $e->getMessage() . json_encode($request->validated())
            ])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id )
    {
        
        $group = $this->groupService->show($id);
        
        
        return inertia::render('Groups/Show', [
            'group' => $group
    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGroupRequest $request, int $id): JsonResponse
    {
        try {
            $group = $this->groupService->update($id, $request->validated());
            
            if (!$group) {
                return response()->json([
                    'success' => false,
                    'message' => 'Grupo no encontrado'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $group,
                'message' => 'Grupo actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el grupo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->groupService->delete($id);
            
            if ($deleted === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Grupo no encontrado'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Grupo eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el grupo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add animals to a group
     */
    public function addAnimals(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'animal_ids' => 'required|array',
            'animal_ids.*' => 'exists:animals,id'
        ]);

        try {
            $success = $this->groupService->addAnimals($id, $request->animal_ids);
            
            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Grupo no encontrado'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Animales agregados al grupo exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar animales: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove animals from a group
     */
    public function removeAnimals(Request $request, int $id)
    {
        $request->validate([
            'animal_ids' => 'required|array',
            'animal_ids.*' => 'exists:animals,id'
        ]);

        try {
            $success = $this->groupService->removeAnimals($id, $request->animal_ids);
            
            if (!$success) {
                return back()->withErrors([
                    'error' => 'Grupo no encontrado o error al remover animales'
                ]);
            }

            return back()->with([
                'success' => true,
                'message' => 'Animales removidos del grupo exitosamente'
            ]);
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Error al remover animales: ' . $e->getMessage()
            ]);
        }
    }

    public function exportCSV(int $id)
{
    try {
        $group = $this->groupService->show($id);
        
        if (!$group) {
            return redirect()->back()->withErrors([
                'error' => 'Grupo no encontrado'
            ]);
        }

        // Prepare CSV data
        $csvData = [];
        
        // CSV Headers
        $csvData[] = [
            //'ID Animal',
            'Caravana',
            'Caravana Oficial',
            'Detalle                ',
            //'Raza',
            //'Sexo',
            //'Fecha Nacimiento',
            //'Rodeo',
            //'Cliente',
            //'Fecha Creación'
        ];
        
        // Add animal data
        foreach ($group->animals as $animal) {
            $csvData[] = [
                //$animal->id,
                $animal->caravana ?? 'N/A',
                $animal->caravana_oficial ?? 'N/A',
                '',
                //$animal->raza ?? 'N/A',
                //$animal->sexo ?? 'N/A',
                //$animal->fecha_nacimiento ? date('d/m/Y', strtotime($animal->fecha_nacimiento)) : 'N/A',
                //$group->rodeo->name ?? 'N/A',
                //$group->rodeo->client ? $group->rodeo->client->name . ' ' . $group->rodeo->client->last_name : 'N/A',
                //$animal->created_at ? date('d/m/Y H:i', strtotime($animal->created_at)) : 'N/A'
            ];
        }
        
        // Generate CSV content
        $output = fopen('php://temp', 'w+');
        
        // Add BOM for UTF-8 support in Excel
        fwrite($output, "\xEF\xBB\xBF");
        
        foreach ($csvData as $row) {
            fputcsv($output, $row, ';'); // Using semicolon for better Excel compatibility
        }
        
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);
        
        // Generate filename
        $filename = 'grupo_' . $group->name . '_animales_' . date('Y-m-d_H-i-s') . '.csv';
        $filename = preg_replace('/[^a-zA-Z0-9_\-.]/', '_', $filename); // Clean filename
        
        // Return CSV download response
        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
            
    } catch (\Exception $e) {
        return redirect()->back()->withErrors([
            'error' => 'Error al exportar CSV: ' . $e->getMessage()
        ]);
    }
}
}