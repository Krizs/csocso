<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Team;
use Filament\Tables;
use App\Models\Contest;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Championship;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\ContestResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ContestResource\RelationManagers;

class ContestResource extends Resource
{
    protected static ?string $model = Contest::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-4';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Meccs résztvevők')
                ->schema([
                    Select::make('championship_id')
                    ->relationship('championship','name')
                    ->label('Bajnokság neve')
                    ->disabled(),
                    Select::make('team_a_id')
                    ->relationship('teamA','name')
                    ->label('A Csapat')
                    ->disabled(),
                    Select::make('team_b_id')
                    ->relationship('teamB','name')
                    ->label('B Csapat')
                    ->disabled(),   
                ]),
                Section::make('Meccs eredmények')
                ->schema([
                    Select::make('winner_id')
                    ->relationship('winner','name')
                    ->options(function(Get $get){
                        $teamAId = $get('team_a_id');
                        $teamBId = $get ('team_b_id');
                        

                        return Team::whereIn('id', [$teamAId,$teamBId])
                        ->pluck('name','id');
                    })
                    ->label('Győztes Csapat'),
                    Select::make('team_a_score')
                    ->label(function(Get $get){
                        $teamAId = $get('team_a_id');
                        return Team::where('id',$teamAId)->pluck('name','id')->first() . " elért pontszáma";
                    })
                    ->options(function(Get $get){
                        $otherScore = $get('team_b_score');
                        if ($otherScore == 10) {
                            $scores = Contest::scores();
                            array_pop($scores);
                            return $scores;
                        }
                        return Contest::scores();
                    })
                    ->reactive(),
                    Select::make('team_b_score')
                    ->label(function(Get $get){
                        $teamBId = $get('team_b_id');
                        return Team::where('id',$teamBId)->pluck('name','id')->first() . " elért pontszáma";
                    })
                    ->options(function(Get $get){
                        $otherScore = $get('team_a_score');
                        if ($otherScore == 10) {
                            $scores = Contest::scores();
                            array_pop($scores);
                            return $scores;
                        }
                        return Contest::scores();
                    })
                    ->reactive(),
                    DateTimePicker::make('played_at')
                    ->afterStateHydrated(function (DateTimePicker $component, ?string $state) {
                        
                        if (!$state) {
                            $component->state(now()->toDateTimeString());
                        }})
                    ->native(false)
                    ->minDate(function(Get $get){
                        $champId = $get('championship_id');
                       return Championship::where('id', $champId)->pluck('date')->first();
                    })
                    ->format('Y-m-d H:i')
                    ->seconds(false)
                    ->label('Ekkor játszva')

                ]),
                   
               
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('championship.name')
                ->searchable()
                ->label('Bajnokság név'),
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
                ->label('Játszva ekkor'),

            ])
            ->filters([
                SelectFilter::make('championship_id')
                    ->preload()
                    ->searchable()
                    ->label('Bajnokság neve')
                    ->options(fn (): array => Championship::query()->pluck('name', 'id')->all()),
                Filter::make('played_at')
                    ->label('Csak játszott')
                    ->query(fn(Builder $query) =>  $query->where('played_at','!=', null))
                    ->toggle()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContests::route('/'),
            'create' => Pages\CreateContest::route('/create'),
            'edit' => Pages\EditContest::route('/{record}/edit'),
        ];
    }
}
