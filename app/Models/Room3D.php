<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Room3D extends Model
{
    protected $table = 'rooms3D';
    
    protected $fillable = [
        'name', 'color', 'size', 'position'
    ];

    protected $casts = [
        'size' => 'array',
        'position' => 'array'
    ];

    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}