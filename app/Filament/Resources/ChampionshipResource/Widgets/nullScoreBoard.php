<?php

namespace App\Filament\Resources\ChampionshipResource\Widgets;

use App\Models\Team;
use Illuminate\Database\Eloquent\Model;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class nullScoreBoard extends BaseWidget
{
    public ?Model $record = null;
    
    protected function getStats(): array
    {
        return [
            Stat::make('Mászó tábla',Team::getNullScoreBoard($this->record->id))
        ];
    }
}
