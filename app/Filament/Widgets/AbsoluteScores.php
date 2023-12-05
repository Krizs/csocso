<?php

namespace App\Filament\Widgets;

use App\Models\Team;
use App\Models\Contest;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class AbsoluteScores extends BaseWidget
{
    protected function getStats(): array
    {
        if ($this->getMostWinsQuery()) {
            return [
                Stat::make('Abszolút Nyertes',$this->getMostWinsQuery()->winner->name.': '. $this->getMostWinsQuery()->wins. 'pont' )
            ];
        }else {
            return [
                Stat::make('Abszolút Nyertes', 'Nincs jelenleg')
            ];
        }
       
    }


    protected function getMostWinsQuery()
    {
        $contest = Contest::with('winner')
        ->select('winner_id', DB::raw('COUNT(*) as wins'))
        ->groupBy('winner_id')
        ->orderByDesc('wins')
        ->first();

        return $contest ?? false;
    }

}
