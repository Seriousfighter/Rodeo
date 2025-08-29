<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Services\DietApiService;
use App\Services\Interfaces\AnimalInterface;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DietApiController extends Controller
{
    private $dietApiService;

    public function __construct(DietApiService $dietApiService)
    {
        $this->dietApiService = $dietApiService;
    }

    public function index(Request $request): JsonResponse
    {
        $rodeo_id = $request->get('rodeo_id', 1);
        
        try {
            $result = $this->dietApiService->getRodeoDiets($rodeo_id);
            
            if ($result['success']) {
                $simplifiedData = $this->simplifyGroupDiets($result['data']);
                
                return response()->json([
                    'success' => true,
                    'data' => $simplifiedData,
                    'total' => count($simplifiedData)
                ]);
            }
            
            return response()->json($result);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las dietas: ' . $e->getMessage()
            ], 500);
        }
    }

    private function simplifyGroupDiets($groupDiets): array
    {
        $simplified = [];

        foreach ($groupDiets as $group) {
            $simplifiedGroup = [
                'group_id' => $group['group_id'],
                'group_name' => $group['group_name'],
                'diets' => []
            ];

            // Solo procesar grupos que tengan dietas
            if (!empty($group['diets'])) {
                foreach ($group['diets'] as $diet) {
                    $simplifiedDiet = [
                        'diet_name' => $diet['name'],
                        'components' => []
                    ];

                    // Simplificar componentes
                    foreach ($diet['components'] as $component) {
                        $simplifiedDiet['components'][] = [
                            'name' => $component['name'],
                            'amount' => $component['amount'],
                            'unit' => $component['unit']
                        ];
                    }

                    $simplifiedGroup['diets'][] = $simplifiedDiet;
                }
            }

            $simplified[] = $simplifiedGroup;
        }

        return $simplified;
    }

    //mocked data
     public function mockData(Request $request): JsonResponse
    {
        $rodeo_id = $request->get('rodeo_id', 1);
        
        // Datos de prueba hardcodeados
        $mockData = [
            [
                'group_name' => 'Vacas Lecheras',
                'group_id' => 1,
                'qty' => 28,
                'diets' => [
                    [
                        'diet_name' => 'Dieta Base Lecheras',
                        'diet_id' => 101,
                        'components' => [
                            [
                                'name' => 'Silo de Maíz',
                                'amount' => '24',
                                'unit' => 'kg'
                            ],
                            [
                                'name' => 'Alfalfa Picada',
                                'amount' => '8',
                                'unit' => 'kg'
                            ],
                            [
                                'name' => 'Expeller de Soja',
                                'amount' => '3',
                                'unit' => 'kg'
                            ],
                            [
                                'name' => 'Núcleo Vitamínico',
                                'amount' => '0.4',
                                'unit' => 'kg'
                            ]
                        ]
                    ],
                    [
                        'diet_name' => 'Dieta Lluvia',
                        'diet_id' => 102,
                        'components' => [
                            [
                                'name' => 'Silo de Maíz',
                                'amount' => '30',
                                'unit' => 'kg'
                            ],
                            [
                                'name' => 'Rollo de Alfalfa',
                                'amount' => '5',
                                'unit' => 'kg'
                            ],
                            [
                                'name' => 'Núcleo Especial',
                                'amount' => '0.5',
                                'unit' => 'kg'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'group_name' => 'Recría',
                'group_id' => 2,
                'qty' => 15,
                'diets' => [
                    [
                        'diet_name' => 'Recría 150-250kg',
                        'diet_id' => 201,
                        'components' => [
                            [
                                'name' => 'Silo de Maíz',
                                'amount' => '12',
                                'unit' => 'kg'
                            ],
                            [
                                'name' => 'Rollo Molido',
                                'amount' => '6',
                                'unit' => 'kg'
                            ],
                            [
                                'name' => 'Núcleo Crecimiento',
                                'amount' => '0.2',
                                'unit' => 'kg'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'group_name' => 'Preparto',
                'group_id' => 3,
                'qty' => 10,
                'diets' => [
                    [
                        'diet_name' => 'Preparto -30 días',
                        'diet_id' => 301,
                        'components' => [
                            [
                                'name' => 'Silo de Maíz',
                                'amount' => '18',
                                'unit' => 'kg'
                            ],
                            [
                                'name' => 'Rollo de Moha',
                                'amount' => '4',
                                'unit' => 'kg'
                            ],
                            [
                                'name' => 'Núcleo Preparto',
                                'amount' => '0.3',
                                'unit' => 'kg'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'group_name' => 'Toros',
                'group_id' => 4,
                'qty' => 5,
                'diets' => [
                    [
                        'diet_name' => 'Mantenimiento Toros',
                        'diet_id' => 401,
                        'components' => [
                            [
                                'name' => 'Pastoreo',
                                'amount' => '0',
                                'unit' => 'kg'
                            ],
                            [
                                'name' => 'Suplemento Mineral',
                                'amount' => '0.15',
                                'unit' => 'kg'
                            ]
                        ]
                    ]
                ]
            ]
        ];
        return response()->json([
                    'success' => true,
                    'data' => $mockData,
                    'total' => count($mockData),
                    'rodeo_id' => $rodeo_id,
                    'mock' => true  // ✅ Indicador de que son datos de prueba
                ]);
    }
    public function mockDataPost(Request $request): JsonResponse
    {
        $data = $request->all();
        Log::info('Datos recibidos en mockDataPost:', $data);
        return response()->json([
            'success' => true,
            'message' => 'Datos recibidos correctamente',
            'received_data' => $data
        ]);
    }
}
