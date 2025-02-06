<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use HasFactory;
class File extends Model
{
    
    protected $fillable = ['name', 'path', 'user_id'];
}
