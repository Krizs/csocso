<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use App\Models\Team;
use Filament\Tables;
use App\Models\Player;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TeamResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TeamResource\RelationManagers;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                ->required()
                ->unique(ignorable: fn ($record) => $record)
                ->label('Csapat neve'),
                Select::make('attacker_id')
                ->relationship('attacker','name')
                ->options(Player::FreePlayers()->where('position','attacker')->pluck('name','id'))
                ->searchable()
                ->required()
                ->preload()
                ->label('Csatár'),
                Select::make('goalkeeper_id')
                ->relationship('goalkeeper','name')
                ->options(Player::FreePlayers()->where('position','goalkeeper')->pluck('name','id'))
                ->searchable()
                ->required()
                ->preload()
                ->label('Kapus'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->label('Csapat név'),
                TextColumn::make('goalkeeper.name')
                ->label('Kapus név'),
                TextColumn::make('attacker.name')
                ->label('Csatár név'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
}
