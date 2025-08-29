<?php

namespace App\Services;

use App\Models\Group;
use App\Services\Interfaces\GroupInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GroupService implements GroupInterface
{
    /**
     * Create a new group with related data
     * 
     * @param array $data Group data including name, description, and animals
     * @return \App\Models\Group
     */
    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            $group = Group::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'rodeo_id'=> $data['rodeo_id'],
            ]);
            
            // Add animals if provided
            if (isset($data['animals']) && is_array($data['animals'])) {
                $group->Animals()->attach($data['animals']);
            }

            return $group->load('Animals');
        });
    }

    /**
     * Update an existing group
     * 
     * @param int $id Group ID to update
     * @param array $data Updated group data
     * @return \App\Models\Group|null
     */
    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $group = Group::find($id);
            
            if (!$group) {
                return null;
            }

            $group->update([
                'name' => $data['name'] ?? $group->name,
                'description' => $data['description'] ?? $group->description,
                'rodeo_id' => $data['rodeo_id'] ?? $group->rodeo_id,
            ]);

            // Update animals if provided
            if (isset($data['animals']) && is_array($data['animals'])) {
                $group->Animals()->sync($data['animals']);
            }

            return $group->load('Animals');
        });
    }

    /**
     * Delete a group by ID
     * 
     * @param int $id Group ID to delete
     * @return bool|null
     */
    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $group = Group::find($id);
            
            if (!$group) {
                return null;
            }

            // Detach all animals first
            $group->Animals()->detach();
            
            return $group->delete();
        });
    }

    /**
     * Get a group with its relationships
     * 
     * @param int $id Group ID to retrieve
     * @return \App\Models\Group|null
     */
    public function show($id)
    {
        return Group::with('Animals', 'Rodeo')->find($id);
    }

    /**
     * Get all groups with their relationships
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Group::with('Animals')->get();
    }

    /**
     * Find a group by ID with its relationships
     * 
     * @param int $id Group ID to find
     * @return \App\Models\Group|null
     */
    public function findById($id)
    {
        return Group::with('Animals')->find($id);
    }
    public function findByRodeoId($rodeoId)
    {
        $groups = Group::with('Animals')
            ->where('rodeo_id', $rodeoId)
            ->get();
        try{
            return Group::with('Animals')
                ->where('rodeo_id', $rodeoId)
                ->get();
        } catch (\Exception $e) {
            dd('error aca');
            return null; // Handle the exception as needed
        }
       
       
    }

    /**
     * Add animals to a group
     * 
     * @param int $groupId Group ID to add animals to
     * @param array $animalIds Array of animal IDs to add
     * @return bool
     */
    public function addAnimals($groupId, array $animalIds)
    {
        $group = Group::find($groupId);
        
        if (!$group) {
            return false;
        }

        $group->Animals()->attach($animalIds);
        return true;
    }

    /**
     * Remove animals from a group
     * 
     * @param int $groupId Group ID to remove animals from
     * @param array $animalIds Array of animal IDs to remove
     * @return bool
     */
    public function removeAnimals($groupId, array $animalIds)
    {
        $group = Group::find($groupId);
        
        if (!$group) {
            return false;
        }

        $group->Animals()->detach($animalIds);
        return true;
    }

    /**
     * Get all animals in a group
     * 
     * @param int $groupId Group ID to get animals for
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAnimals($groupId)
    {
        $group = Group::find($groupId);
        
        if (!$group) {
            return new Collection();
        }

        return $group->Animals;
    }

    /**
     * Replace all animals in a group
     * 
     * @param int $groupId Group ID to replace animals for
     * @param array $animalIds Array of animal IDs to set
     * @return bool
     */
    public function replaceAnimals($groupId, array $animalIds)
    {
        $group = Group::find($groupId);
        
        if (!$group) {
            return false;
        }

        $group->Animals()->sync($animalIds);
        return true;
    }

    public function getAnimalQuantity($groupId){
        $group = Group::find($groupId);
        
        if (!$group) {
            return 0;
        }

        return $group->Animals()->count();
    }
}