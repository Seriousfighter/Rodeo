<?php

namespace App\Services\Interfaces;

interface GroupInterface
{
    /**
     * Create a new group with related data
     * 
     * @param array $data Group data including name, description, and animals
     * @return \App\Models\Group
     */
    public function store(array $data);
    
    /**
     * Update an existing group
     * 
     * @param int $id Group ID to update
     * @param array $data Updated group data
     * @return \App\Models\Group|null
     */
    public function update($id, array $data);
    
    /**
     * Delete a group by ID
     * 
     * @param int $id Group ID to delete
     * @return bool|null
     */
    public function delete($id);
    
    /**
     * Get a group with its relationships
     * 
     * @param int $id Group ID to retrieve
     * @return \App\Models\Group|null
     */
    public function show($id);
    
    /**
     * Get all groups with their relationships
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index();

    /**
     * Find a group by ID with its relationships
     * 
     * @param int $id Group ID to find
     * @return \App\Models\Group|null
     */
    public function findById($id);

    /**
     * Add animals to a group
     * 
     * @param int $groupId Group ID to add animals to
     * @param array $animalIds Array of animal IDs to add
     * @return bool
     */
    public function addAnimals($groupId, array $animalIds);

    /**
     * Remove animals from a group
     * 
     * @param int $groupId Group ID to remove animals from
     * @param array $animalIds Array of animal IDs to remove
     * @return bool
     */
    public function removeAnimals($groupId, array $animalIds);

    /**
     * Get all animals in a group
     * 
     * @param int $groupId Group ID to get animals for
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAnimals($groupId);

    /**
     * Replace all animals in a group
     * 
     * @param int $groupId Group ID to replace animals for
     * @param array $animalIds Array of animal IDs to set
     * @return bool
     */
    public function replaceAnimals($groupId, array $animalIds);

    public function findByRodeoId($rodeoId);
}