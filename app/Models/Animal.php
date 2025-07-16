<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rodeo;


class Animal extends Model
{
    /** @use HasFactory<\Database\Factories\AnimalFactory> */
    use HasFactory;
    protected $fillable = [
        'caravana',
        'caravana_oficial',
        'rodeo_id',
    ];
    //belongs to a rodeo
    public function rodeo()
    {
        return $this->belongsTo(Rodeo::class);
    }
    public function Groups (){
        return $this->belongsToMany(Group::class);
    }
}
