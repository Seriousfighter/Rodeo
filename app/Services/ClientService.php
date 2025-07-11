<?php
namespace App\Services;
use App\Models\Client;
use Illuminate\Support\Facades\Storage;
use App\Services\Interfaces\ClientInterface;

/*this is a ClientService it will manage all the crud and other funciton, then in the controller i will call this services<?php*/

class ClientService implements ClientInterface
{
    public function index()
    {
       //$clientes = Client::with(['rodeos'])->get();
       $clientes = Client::with(['rodeos'])->get();
        if (!$clientes) {
            return null;
        }
        
        return $clientes;
    }
    public function show($id)
    {
       $client = $this->findById($id);
        if (!$client) {
            return null;
        }
        
        return $client;
    }

    public function store($data)
    {
        $cliente = new Client();
        $cliente->name = $data['name'];
        $cliente->last_name = $data['last_name'];
        $cliente->email = $data['email'];
        $cliente->phone = $data['phone'];
        
        if ($cliente->save()) {
            return $cliente;
        }
        
        return null;
    
    }

    
    public function update($id, $data)
    {
        $cliente = $this->findById($id);
        
        if (!$cliente) {
            return null;
        }
        
        $cliente->name = $data['name'];
        $cliente->last_name = $data['last_name'];
        $cliente->email = $data['email'];
        $cliente->phone = $data['phone'];
        
        if ($cliente->save()) {
            return $cliente;
        }
        
        return null;
    }
    public function delete($id)
    {
        $cliente = $this->findById($id);
        
        if (!$cliente) {
            return null;
        }
        
        if ($cliente->delete()) {
            return true;
        }
        
        return false;
    }

    public function findById($id)
    {
        $Client = Client::with(['rodeos'])->find($id);
        
        if (!$Client) {
            return null;
        }
        
        return $Client;
    }

    
}
