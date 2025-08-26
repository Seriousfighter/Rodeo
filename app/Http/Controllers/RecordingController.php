<?php

namespace App\Http\Controllers;

use App\Services\RecordingApiService;
use App\Services\Interfaces\AnimalInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;
/**
 * debo crear componente para vista que informe en caso de estar desconectada la api
 * esto debe servir para que todo sea independiente y modular, asi poder ofrecer diversas opciones
 * en este caso solo index al no poder conectarse, laza un dd("modulo desconectado"),
 * se me ocurre que podria hacer un check health a la api o demas y utilizarlo para mostrar un mensaje mas amigable
 *
 */
class RecordingController extends Controller
{
    private $recordingApi;
    protected AnimalInterface $animalService;

    public function __construct(RecordingApiService $recordingApi, AnimalInterface $animalService,)
    {
        $this->recordingApi = $recordingApi;
         $this->animalService = $animalService;
    }

    public function index(Request $request)
    {
        try {
            $filters = $request->only(['animal_id', 'rodeo_id', 'client_id', 'recording_type', 'status']);
            $recordings = $this->recordingApi->index($filters);
            
            return Inertia::render('Recordings/Index', [
                'recordings' => $recordings
            ]);
        } catch (\Exception $e) {
            return Inertia::render('Recordings/Index', [
                'recordings' => [
                    'success' => false,
                    'error' => $e->getMessage(),
                    'data' => []
                ]
            ]);
        }
    }

    public function show($id)
    {
        try {
            $recording = $this->recordingApi->show($id);
            
            return Inertia::render('Recordings/Show', [
                'recording' => $recording
            ]);
        } catch (\Exception $e) {
            return redirect()->route('recordings.index')->with('error', $e->getMessage());
        }
    }

    public function create($animalId)
    {
        try {
            $animal = $this->animalService->findById($animalId);

            $animal_id = $animal->id;
            $client_id = $animal->rodeo->client_id;
            $rodeo_id = $animal->rodeo_id;
            
            $ids = [
                'animal_id' => $animal_id,
                'client_id' => $client_id,
                'rodeo_id' => $rodeo_id
            ];
            $recording = [
                'animal_id' => $animal_id,
                'rodeo_id' => $rodeo_id,
                'client_id' => $client_id,
                
            ];
            return Inertia::render('Recordings/save', ['ids' => $ids, 'recording' => $recording]);
        } catch (\Exception $e) {
            return redirect()->route('recordings.index')->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        //dd($request->all());
        try {
            $data = $request->validate([
                'animal_id' => 'required|integer',  
                'rodeo_id' => 'required|integer',
                'client_id' => 'required|integer',
                'recording_type' => 'required|string',
                'recording_data' => 'required|array',
                'veterinarian_id' => 'required|integer',
                'notes' => 'nullable|string',
                'status' => 'nullable|string',
                //'recording_date' => 'required|date_format:d-m-y'
            ]);

            $result = $this->recordingApi->store($data);
            
            if ($result['success']) {
                return redirect()->route('animals.show', $data['animal_id'])->with('success', 'Registro creado exitosamente');
            } else {
                return back()->withErrors(['error' => $result['error'] ?? 'Error al crear el registro']);
            }
        } catch (\Exception $e) {
            dd('error');
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        //dd('here');
        try {
            $recording = $this->recordingApi->show($id);
//            dd($recording['data']['animal_id']);
            $recording = $recording['data'];
         return Inertia::render('Recordings/save', ['recording' => $recording]);
        } catch (\Exception $e) {
            return redirect()->route('recordings.index')->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'animal_id' => 'required|integer',
                'rodeo_id' => 'required|integer',
                'client_id' => 'required|integer',
                'recording_type' => 'required|string',
                'recording_data' => 'required|array',
                'veterinarian_id' => 'required|integer',
                'notes' => 'nullable|string',
                'status' => 'nullable|string'
            ]);

            $result = $this->recordingApi->update($id, $data);
            
            if ($result['success']) {
                return redirect()->route('animals.show', $data['animal_id'])->with('success', 'Registro modificado exitosamente');
            } else {
                return back()->withErrors(['error' => $result['error'] ?? 'Error al actualizar el registro']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            
            $result = $this->recordingApi->delete($id);
            
            if ($result['success']) {
                return back()->with('success', 'Registro eliminado exitosamente');
            } else {
                return back()->withErrors(['error' => $result['error'] ?? 'Error al eliminar el registro']);
            }
        } catch (\Exception $e) {
            
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function bulkStore(Request $request)
    {
       // dd($request->all());
        try {
            $data = $request->validate([
                'recordings' => 'required|array',
                'recordings.*.animal_id' => 'required|integer',
                'recordings.*.rodeo_id' => 'required|integer',
                'recordings.*.client_id' => 'required|integer',
                'recordings.*.recording_type' => 'required|string',
                'recordings.*.recording_data' => 'array',
                'recordings.*.veterinarian_id' => 'required|integer',
                'recordings.*.notes' => 'nullable|string',
                'recordings.*.status' => 'nullable|string',
                'recordings.*.recording_date' => 'required|date'
            ]);

            $results = [];
            foreach ($data['recordings'] as $recordingData) {
                $result = $this->recordingApi->store($recordingData);
                $results[] = $result;
            }

            $successCount = count(array_filter($results, fn($r) => $r['success']));

            return redirect()->back()->with('success', $successCount .' registros creados exitosamente');

        }catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Error al cargar los datos: ' . $e->getMessage()
            ]);
        }
}
}