<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Services\Interfaces\ClientInterface;
use Inertia\Inertia;

class ClientController extends Controller
{
    protected ClientInterface $clientService;
    
    public function __construct(ClientInterface $clientService)
    {
        $this->clientService = $clientService;
    }    
    
    public function index()
    {
        try {
            $clientes = $this->clientService->index();
            return Inertia::render('Clients/Index', [
                'clientes' => $clientes,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Error al cargar los clientes: ' . $e->getMessage()
            ]);
        }
    }

    public function create()
    {
        try {
            return Inertia::render('Clients/Save');
        } catch (\Exception $e) {
            return redirect()->route('clients.index')->withErrors([
                'error' => 'Error al cargar el formulario de creación: ' . $e->getMessage()
            ]);
        }
    }

    public function store(StoreClientRequest $request)
    {
        try {
            $client = $this->clientService->store($request->validated());
            return redirect()->route('clients.index')->with('success', 'Cliente creado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Error al crear el cliente: ' . $e->getMessage()
            ])->withInput();
        }
    }

    public function show(Client $client)
    {
        try {
            $clientData = $this->clientService->show($client->id);
            return Inertia::render('Clients/Show', [
                'client' => $clientData,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('clients.index')->withErrors([
                'error' => 'Error al cargar el cliente: ' . $e->getMessage()
            ]);
        }
    }

    public function edit(Client $client)
    {
        try {
            $clientData = $this->clientService->show($client->id);
            return Inertia::render('Clients/Save', [
                'client' => $clientData,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('clients.index')->withErrors([
                'error' => 'Error al cargar el formulario de edición: ' . $e->getMessage()
            ]);
        }
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        try {
            $updatedClient = $this->clientService->update($client->id, $request->validated());
            return redirect()->route('clients.index')->with('success', 'Cliente actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Error al actualizar el cliente: ' . $e->getMessage()
            ])->withInput();
        }
    }

    public function destroy(Client $client)
    {
        try {
            $this->clientService->delete($client->id);
            return redirect()->route('clients.index')->with('success', 'Cliente eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Error al eliminar el cliente: ' . $e->getMessage()
            ]);
        }
    }
}