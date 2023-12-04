<?php

namespace App\Filament\Resources\ChampionshipResource\Widgets;

use App\Models\Team;
use Illuminate\Database\Eloquent\Model;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ScoreBoard extends BaseWidget
{
    

    public ?Model $record = null;
   
    protected function getStats(): array
    {
        return [
            Stat::make('Eredménytábla',Team::getScoreBoard($this->record->id))
        ];
    }
}
