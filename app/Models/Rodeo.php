<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rodeo extends Model
{
    /** @use HasFactory<\Database\Factories\RodeoFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'location',
        'description',
        'renspa',
        'client_id',
    ];


    //relationshiop
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    //has many animals
    public function animals()
    {
        return $this->hasMany(Animal::class);
    }
    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}
