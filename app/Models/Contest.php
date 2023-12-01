<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{

    protected $table = 'matches'; // needed cuz The name "Match" is reserved by PHP.

    protected $fillable = [
        'championship_id',
        'team_a_id',
        'team_b_id',
        'winner_id'

    ];

    public function championship()
    {
        return $this->belongsTo(Championship::class);
    }

    public function teamA()
    {
        return $this->belongsTo(Team::class,'team_a_id');
    }

    public function teamB()
    {
        return $this->belongsTo(Team::class,'team_b_id');
    }

    public function winner()
    {
        return $this->belongsTo(Team::class,'winner_id');
    }

    use HasFactory;
}
