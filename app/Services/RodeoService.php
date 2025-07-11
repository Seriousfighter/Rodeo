<?php

namespace App\Services;

use App\Models\Rodeo;
use App\Services\Interfaces\RodeoInterface;

class RodeoService implements RodeoInterface
{
    public function index()
    {
        $rodeos = Rodeo::with(['client', 'animals'])->get();
        if (!$rodeos) {
            return null;
        }
        
        return $rodeos;
    }

    public function show($id)
    {
        $rodeo = $this->findById($id);
        if (!$rodeo) {
            return null;
        }
        
        return $rodeo;
    }

    public function store(array $data)
    {
        $rodeo = new Rodeo();
        $rodeo->name = $data['name'];
        $rodeo->location = $data['location'] ?? null;
        $rodeo->description = $data['description'] ?? null;
        $rodeo->renspa = $data['renspa'] ?? null;
        $rodeo->client_id = $data['client_id'];
        
        if ($rodeo->save()) {
            return $rodeo;
        }
        
        return null;
    }

    public function update($id, array $data)
    {
        $rodeo = $this->findById($id);
        
        if (!$rodeo) {
            return null;
        }
        
        $rodeo->name = $data['name'];        
        $rodeo->location = $data['location'] ?? null;
        $rodeo->description = $data['description'] ?? null;
        $rodeo->renspa = $data['renspa'] ?? null;
        $rodeo->client_id = $data['client_id'];
        
        if ($rodeo->save()) {
            return $rodeo;
        }
        
        return null;
    }

    public function delete($id)
    {
        $rodeo = $this->findById($id);
        
        if (!$rodeo) {
            return null;
        }
        
        if ($rodeo->delete()) {
            return true;
        }
        
        return false;
    }

    public function findById($id)
    {
        $rodeo = Rodeo::with(['client', 'animals'])->find($id);
        
        if (!$rodeo) {
            return null;
        }
        
        return $rodeo;
    }
}