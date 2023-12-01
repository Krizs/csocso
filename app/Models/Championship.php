<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Championship extends Model
{

    protected $fillable = [
        'name',
        'date',
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public function contests()
    {
        return $this->belongsToMany(Contest::class);
    }

    use HasFactory;
}
