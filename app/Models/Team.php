<?php

namespace App\Models;

use App\Models\Player;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{
    use HasFactory;

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

    public function contests()
    {
        return $this->belongsToMany(Contest::class);
    }

    public function championships()
    {
        return $this->belongsToMany(Championship::class);
    }

    public static function getScoreBoard($championshipId)
    {
        
       $teams = Team::whereHas('championships', function ($query) use ($championshipId) {
            $query->where('championship_id', $championshipId);
        })->get();
        
        $contests = Contest::where('championship_id',$championshipId)->get();

        foreach ($teams as $team) {
            $teamWins = $contests->whereIn('winner_id',$team->id)->count();
            $wins[$team->name] = $teamWins;
        }

        arsort($wins);

        $winner = Self::checkEquality($contests,$wins);
       
        if ($winner != null) {
                $wins[$winner->name] += 1;
                arsort($wins);
        }

        $string = '';
        foreach ($wins as $key => $value) {
            $string .= $key . ' : ' . $value . "p, \r\n";
        }
    

        return $string;
    }

    protected static function checkEquality($contests,$wins) {

        if ($contests->where('winner_id','!=',null)->count() === $contests->count()) {
            if (count(array_unique($wins)) !== 1) {

                $lastTwoScores = array_slice($wins,0,2);
                $firstValue = reset($lastTwoScores);
                $lastValue = end($lastTwoScores); 

                if ($firstValue === $lastValue) {
                    $teams = Team::whereIn('name',array_keys($lastTwoScores))->get();
                    foreach ($teams as $team) {
                        $teamIds[] = $team->id;
                    }
                   
                    $fightEachOther = $contests->where('team_a_id', $teamIds[0])->where('team_b_id',$teamIds[1]);

                    if ($fightEachOther->count() == 0) {
                        $fightEachOther = $contests->where('team_a_id', $teamIds[1])->where('team_b_id',$teamIds[0]);
                    } 

                   $winner = $teams->where('id',$fightEachOther->first()->winner_id)->first();
                   return $winner;
                }
            }
        }
    }

    public static function getNullScoreBoard($championshipId)
    {
        $teams = Team::whereHas('championships', function ($query) use ($championshipId) {
            $query->where('championship_id', $championshipId);
        })->get();
        
        $contests = Contest::where('championship_id',$championshipId)->get();

        $zeroScores = [];

        foreach ($teams as $team) {
            $teamContests = $contests->filter(function ($contest) use ($team) {
                return $contest->team_a_id === $team->id || $contest->team_b_id === $team->id;
            });
        
            $zeroScores[$team->name] = $teamContests->filter(function ($contest) use ($team) {
                return ($contest->team_a_id === $team->id && $contest->team_a_score === 0) || 
                       ($contest->team_b_id === $team->id && $contest->team_b_score === 0);
            })->count();
    
        }

        arsort($zeroScores);
     
        $string = '';

        foreach ($zeroScores as $key => $value) {
            $string .= $key . ' : ' . $value . "X, \r\n";
        }

        return $string;
    }

  
}
