<?php

namespace App\Models;

use App\Models\Player;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{

    protected $fillable = [
        'name',
        'attacker_id',
        'goalkeeper_id'
    ];

    public function attacker()
    {
        return $this->belongsTo(Player::class, 'attacker_id');
    }

    public function goalkeeper()
    {
        return $this->belongsTo(Player::class, 'goalkeeper_id');
    }

    public function match()
    {
        return $this->belongsTo(Contest::class);
    }

    public function championships()
    {
        return $this->belongsToMany(Championship::class);
    }

    use HasFactory;
}
