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
    ];

    public function rodeo()
    {
        return $this->belongsTo(Rodeo::class);
    }   
    
}
