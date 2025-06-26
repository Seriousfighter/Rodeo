<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Services\Interfaces\ClienteInterface;
use Inertia\Inertia;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //each functions will implement interface
    protected ClienteInterface $clientService;
    public function __construct(ClienteInterface $clientService)
    {
        $this->clientService = $clientService;
    }    
    public function index()
    {
        try{
            $clientes = $this->clientService->index();
            return Inertia::Render('Clients/Index', [
                'clientes'=> $clientes,
            ]);
        } catch (\Exception $e) {
            // Handle the exception, log it, or return an error response
            return response()->json(['error' => 'Failed to retrieve clients: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        
        try {
            return Inertia::Render('Clients/Save',['clientes'=> '']);
        } catch (\Exception $e) {
            // Handle the exception, log it, or return an error response
            return response()->json(['error' => 'Failed to load create client form: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        try {
            $client = $this->clientService->store($request->validated());
            return redirect()->route('clients.index')->with('success', 'Client created successfully.');
        } catch (\Exception $e) {
            // Handle the exception, log it, or return an error response
            return redirect()->back()->withErrors(['error' => 'Failed to create client: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        try {
            $client = $this->clientService->show($client->id);
            return Inertia::Render('Clients/Show', [
                'client' => $client,
            ]);
        } catch (\Exception $e) {
            // Handle the exception, log it, or return an error response
            return response()->json(['error' => 'Failed to retrieve client: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        try{
            $client = $this->clientService->show($client->id);
            return Inertia::Render('Clients/Save', [
                'client' => $client,
            ]);
        } catch (\Exception $e) {
            // Handle the exception, log it, or return an error response
            return response()->json(['error' => 'Failed to load edit client form: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        try {
            $client = $this->clientService->update($client->id, $request->validated());
            return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
        } catch (\Exception $e) {
            // Handle the exception, log it, or return an error response
            return redirect()->back()->withErrors(['error' => 'Failed to update client: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        try {
            $this->clientService->delete($client->id);
            return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
        } catch (\Exception $e) {
            // Handle the exception, log it, or return an error response
            return redirect()->back()->withErrors(['error' => 'Failed to delete client: ' . $e->getMessage()]);
        }
    }
}
