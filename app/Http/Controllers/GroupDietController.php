<?php

namespace App\Http\Controllers;

use App\Services\DietApiService;
use App\Services\Interfaces\GroupInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Http\JsonResponse;

class GroupDietController extends Controller
{
    private $dietApi;
    protected GroupInterface $groupService;

    public function __construct(DietApiService $dietApi, GroupInterface $groupService)
    {
        $this->dietApi = $dietApi;
        $this->groupService = $groupService;
    }

    /**
     * Display group diets
     */
    public function index(Request $request, $groupId)
    {
        
        try {
            // Get group info
            $group = $this->groupService->show($groupId);
            if (!$group) {
                return Inertia::render('Groups/Index',)
                    ->withErrors(['error' => 'Grupo no encontrado']);
            }

            // Get group diets from API
            try{
                $groupDiets = $this->dietApi->getGroupDietByGroupId($groupId);
            }catch (\Exception $e) {

                $groupDiets = [];
            }
            // Get all available diets for assignment
            $availableDiets = $this->dietApi->getAllDiets(['status' => 'active']);

            return Inertia::render('Groups/Diets/Index', [
                'group' => $group,
                'groupDiets' => $groupDiets,
                'availableDiets' => $availableDiets
            ]);

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Error al obtener las dietas del grupo: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Assign diet to group
     */
    public function assignDiet(Request $request, $groupId)
    {
        
        $request->validate([
            'diet_id' => 'required|string',
            //'rodeo_id' => 'required|integer',
            'usage_condition' => 'nullable|string|in:normal,rain,hot_weather,cold_weather,drought,winter,summer,emergency,custom',
            'condition_description' => 'nullable|string',
            'priority' => 'nullable|integer|min:1',
            'notes' => 'nullable|string'
        ]);
        try {
            // Get group info
            $group = $this->groupService->show($groupId);
            if (!$group) {
                return response()->json([
                    'success' => false,
                    'message' => 'Grupo no encontrado'
                ], 404);
            }
            
            $assignmentData = [
                'group_id' => (int) $groupId,
                'rodeo_id' => (int) $group->rodeo_id,
                'group_name' => $group->name,
                'group_description' => $group->description ?? '',
                'diet_id' => $request->diet_id,
                'usage_condition' => $request->usage_condition ?? 'normal',
                'condition_description' => $request->condition_description ?? '',
                'priority' => $request->priority ?? 1,
                'created_by' => 1, // TODO: auth()->id()
                'notes' => $request->notes ?? ''
            ];

            $result = $this->dietApi->assignDietToGroup($assignmentData);
            
            if ($result['success']) {
                return redirect()->back()->with([
                    'success' => true,
                    'message' => 'Dieta asignada al grupo exitosamente'
                ]);
            } else {
                return Inertia::render('Groups/Index', [
                    'success' => false,
                    'message' => $result['error'] ?? 'Error al asignar la dieta'
                ]);
            }

        } catch (\Exception $e) {
            return Inertia::render('Groups/Index', [
                'success' => false,
                'message' => 'Error al asignar la dieta: ' . $e->getMessage()
            ]);
                
        }
    }

    /**
     * Update group diet
     */
    public function updateDiet(Request $request, $groupId, $dietId)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'components' => 'nullable|array',
            'components.*.name' => 'required_with:components|string|max:255',
            'components.*.amount' => 'required_with:components|string|max:50',
            'components.*.unit' => 'required_with:components|string|in:kg,gr,L,ml,units,other',
            'components.*.notes' => 'nullable|string',
            'total_weight' => 'nullable|string|max:50',
            'diet_type' => 'nullable|string|in:maintenance,growth,reproduction,lactation,custom',
            'usage_condition' => 'nullable|string',
            'condition_description' => 'nullable|string',
            'priority' => 'nullable|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        try {
            $updateData = array_filter($request->only([
                'name', 'description', 'components', 'total_weight', 
                'diet_type', 'usage_condition', 'condition_description', 
                'priority', 'notes'
            ]));

            $result = $this->dietApi->updateGroupDiet($groupId, $dietId, $updateData);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Dieta del grupo actualizada exitosamente',
                    'data' => $result['data']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['error'] ?? 'Error al actualizar la dieta'
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la dieta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove diet from group
     */
    public function removeDiet($groupId, $dietId)
    {
        try {
            $result = $this->dietApi->removeGroupDiet($groupId, $dietId);

            if ($result['success']) {
                return redirect()->back()->with([
                    'success' => true,
                    'message' => 'Dieta removida del grupo exitosamente'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['error'] ?? 'Error al remover la dieta'
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al remover la dieta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set active diet for group
     */
    public function setActiveDiet($groupId, $dietId)
    {
        try {
            $result = $this->dietApi->setActiveGroupDiet($groupId, $dietId);

            if ($result['success']) {
                return redirect()->back()->with([
                    'success' => true,
                    'message' => 'Dieta activa cambiada exitosamente',
                    'data' => $result['data']
                ]);     
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['error'] ?? 'Error al cambiar la dieta activa'
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar la dieta activa: ' . $e->getMessage()
            ], 500);
        }
    }
}