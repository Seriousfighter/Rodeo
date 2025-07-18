<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /** @use HasFactory<\Database\Factories\GroupFactory> */
    use HasFactory;


    protected $fillable = [
        'name',
        'description',
        'rodeo_id',
    ];

    public function Animals(){
        return $this->belongsToMany(Animal::class);
    }
    public function rodeo(){
        return $this->belongsTo(Rodeo::class);
    }
}
