<?php

namespace App\Services\Interfaces;

interface RodeoInterface
{
    /**
     * Create a new rodeo with related data
     * 
     * @param array $data Rodeo data including event and participant information
     * @return \App\Models\Rodeo
     */
    public function store(array $data);
    
    /**
     * Update an existing rodeo
     * 
     * @param int $id Rodeo ID to update
     * @param array $data Updated rodeo data
     * @return \App\Models\Rodeo|null
     */
    public function update($id, array $data);
    
    /**
     * Delete a rodeo by ID
     * 
     * @param int $id Rodeo ID to delete
     * @return bool|null
     */
    public function delete($id);
    
    /**
     * Get a rodeo with its relationships
     * 
     * @param int $id Rodeo ID to retrieve
     * @return \App\Models\Rodeo|null
     */
    public function show($id);
    
    /**
     * Get all rodeos with their relationships
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index();

    /**
     * Find a rodeo by ID with its relationships
     * 
     * @param int $id Rodeo ID to find
     * @return \App\Models\Rodeo|null
     */
    public function findById($id);
}