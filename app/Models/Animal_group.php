<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animal_group extends Model
{
    /** @use HasFactory<\Database\Factories\AnimalGroupsFactory> */
    use HasFactory;

    protected $fillable = [
        'animal_id',
        'group_id',
    ];
}
