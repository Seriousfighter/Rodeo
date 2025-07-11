<?php

namespace App\Services\Interfaces;

interface ClientInterface
{
    /**
     * Create a new property with related data
     * 
     * @param array $data Property data including ubicacion and tipo
     * @return \App\Models\Client
     */
    public function store(array $data);
    
    /**
     * Update an existing property
     * 
     * @param int $id Property ID to update
     * @param array $data Updated property data
     * @return \App\Models\Client|null
     */
    public function update($id, array $data);
    
    /**
     * Delete a property by ID
     * 
     * @param int $id Property ID to delete
     * @return bool|null
     */
    public function delete($id);
    
    /**
     * Get a property with its relationships
     * 
     * @param int $id Property ID to retrieve
     * @return \App\Models\Client|null
     */
    public function show($id);
    /**
     * Get all property with its relationships
     * Optionally filtered by seller if user has seller role
     * 
     * @param int|null $sellerId Filter by seller ID if provided
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index();

    /**
     * Find a property by ID with its relationships
     * 
     * @param int $id Property ID to find
     * @return \App\Models\Client|null
     */
    public function findById($id);
}