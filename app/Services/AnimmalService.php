<?php

namespace App\Services;


class AnimalService implements AnimalInterface
{
    public function index()
    {
        $animals = Animal::with(['rodeo'])->get();
        if (!$animals) {
            return null;
        }
        
        return $animals;
    }

    public function show($id)
    {
        $animal = $this->findById($id);
        if (!$animal) {
            return null;
        }
        
        return $animal;
    }

    public function store($data)
    {
        $animal = new Animal();
        $animal->caravana = $data['caravana'];
        $animal->caravana_oficial = $data['caravana_oficial'];
        $animal->rodeo_id = $data['rodeo_id'];
        
        if ($animal->save()) {
            return $animal;
        }
        
        return null;
    }

    public function update($id, $data)
    {
        $animal = $this->findById($id);
        
        if (!$animal) {
            return null;
        }
        
        $animal->caravana = $data['caravana'];
        $animal->caravana_oficial = $data['caravana_oficial'];
        $animal->rodeo_id = $data['rodeo_id'];
        
        if ($animal->save()) {
            return $animal;
        }
        
        return null;
    }

    public function delete($id)
    {
        $animal = $this->findById($id);
        
        if (!$animal) {
            return null;
        }
        
        if ($animal->delete()) {
            return true;
        }
        
        return false;
    }

    public function findById($id)
    {
        $animal = Animal::with(['rodeo'])->find($id);
        
        if (!$animal) {
            return null;
        }
        
        return $animal;
    }
}