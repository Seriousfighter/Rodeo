<?php

namespace App\Http\Controllers;

use App\Services\RecordingApiService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RecordingController extends Controller
{
    private $recordingApi;

    public function __construct(RecordingApiService $recordingApi)
    {
        $this->recordingApi = $recordingApi;
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

    public function create()
    {
        return Inertia::render('Recordings/save');
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
                return redirect()->route('recordings.index')->with('success', 'Registro creado exitosamente');
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
        try {
            $recording = $this->recordingApi->show($id);
            
            return Inertia::render('Recordings/Edit', [
                'recording' => $recording
            ]);
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
                return redirect()->route('recordings.index')->with('success', 'Registro actualizado exitosamente');
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
                return redirect()->route('recordings.index')->with('success', 'Registro eliminado exitosamente');
            } else {
                return back()->withErrors(['error' => $result['error'] ?? 'Error al eliminar el registro']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}