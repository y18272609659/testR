<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildingList extends Model
{
    protected $fillable = [
        'userId', 'start', 'end', 'category', 'level', 'action', 'number'
    ];
}
