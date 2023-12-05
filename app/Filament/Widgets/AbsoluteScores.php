<?php

namespace App\Filament\Widgets;

use App\Models\Team;
use App\Models\Contest;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class AbsoluteScores extends BaseWidget
{
    protected $nullScoreAndId = [0];

    protected function getStats(): array
    {
            return [
                Stat::make('Abszolút Nyertes',$this->getMostWinTeamName().': '. $this->getMostWinTeamScore(). ' pont' ),
                Stat::make('Abszolút Mászó',$this->getMostNullScoredTeamName().': '.$this->getMostNullScoredTeamScore(). 'X')
            ];
    
    }


    protected function getMostWinsQuery()
    {
        $contest = Contest::with('winner')
            ->select('winner_id', DB::raw('COUNT(*) as wins'))
            ->groupBy('winner_id')
            ->orderByDesc('wins')
            ->first();

        return $contest ?? null;
    }

    protected function getMostWinTeamName()
    {
      return $this->getMostWinsQuery()->winner->name ?? 'Nincs még';
    }

    protected function getMostWinTeamScore()
    {
        return $this->getMostWinsQuery()->wins ?? '0';
    }

    protected function getMostNullScoresQuery()
    {
        $contestsTeamANullScore = Contest::where('team_a_score',0)->get()->pluck('team_a_id');
        $contestsTeamBNullScore = Contest::where('team_b_score',0)->get()->pluck('team_b_id');

        $nullScoredTeamIds = $contestsTeamANullScore->merge($contestsTeamBNullScore);
        $nullScoredTeamIds = $nullScoredTeamIds->countBy();

        $maxValue = $nullScoredTeamIds->max();
        $mostNullScoredTeam = $nullScoredTeamIds->filter(function ($value) use ($maxValue) {
            return $value === $maxValue;
        })->mapWithKeys(function ($value, $key) {
            return [$key => $value];
        })->all();

        $this->nullScoreAndId = $mostNullScoredTeam;

        return Team::where('id',array_keys($mostNullScoredTeam))->first() ?? null;
    }

    protected function getMostNullScoredTeamName()
    {
        return $this->getMostNullScoresQuery()->name ?? 'Nincs még';
    }

    protected function getMostNullScoredTeamScore()
    { 
        return implode($this->nullScoreAndId);
    }
}
