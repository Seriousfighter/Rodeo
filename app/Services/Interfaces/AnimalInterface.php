<?php

namespace App\Services\Interfaces;

interface AnimalInterface
{
    /**
     * Create a new animal with related data
     * 
     * @param array $data Animal data including breed and owner information
     * @return \App\Models\Animal
     */
    public function store(array $data);
    
    /**
     * Update an existing animal
     * 
     * @param int $id Animal ID to update
     * @param array $data Updated animal data
     * @return \App\Models\Animal|null
     */
    public function update($id, array $data);
    
    /**
     * Delete an animal by ID
     * 
     * @param int $id Animal ID to delete
     * @return bool|null
     */
    public function delete($id);
    
    /**
     * Get an animal with its relationships
     * 
     * @param int $id Animal ID to retrieve
     * @return \App\Models\Animal|null
     */
    public function show($id);
    
    /**
     * Get all animals with their relationships
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index();

    /**
     * Find an animal by ID with its relationships
     * 
     * @param int $id Animal ID to find
     * @return \App\Models\Animal|null
     */
    public function findById($id);
}