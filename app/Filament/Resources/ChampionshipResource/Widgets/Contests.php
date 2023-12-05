<?php

namespace App\Filament\Resources\ChampionshipResource\Widgets;

use Filament\Tables;
use App\Models\Contest;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Actions\Action;

class Contests extends BaseWidget
{

    public ?Model $record = null;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => Contest::whereHas('championship', function($query){
                        $query->where('championship_id',$this->record->id);
                }))
            ->columns([
                TextColumn::make('teamA.name')
                    ->searchable()
                    ->label('A csapat neve'),
                TextColumn::make('teamB.name')
                    ->searchable()
                    ->label('B csapat neve'),
                TextColumn::make('winner.name')
                    ->label('Győztes csapat neve'),
                TextColumn::make('team_a_score')
                    ->label('A csapat pontja'),
                TextColumn::make('team_b_score')
                    ->label('B csapat pontja'),
                TextColumn::make('played_at')
                    ->dateTime('Y-m-d H:i')
                    ->label('Játszva ekkor')
            ])
            ->actions([
                Action::make('edit')
                    ->url(fn (Contest $record): string => route('filament.admin.resources.contests.edit', $record))
            ]);
    }
}
