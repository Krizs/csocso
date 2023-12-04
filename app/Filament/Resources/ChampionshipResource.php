<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Championship;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ChampionshipResource\Pages;
use App\Models\Team;
use Filament\Forms\Components\Select;


class ChampionshipResource extends Resource
{
    protected static ?string $model = Championship::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                ->required()
                ->unique(ignorable: fn ($record) => $record)
                ->label('Bajnokság neve'),
                DateTimePicker::make('date')
                ->native(false)
                ->required()
                ->prefixIcon('heroicon-o-bolt')
                ->prefixIconColor('success')
                ->minutesStep(15)
                ->seconds(false)
                ->minDate(Carbon::now()->format('Y-m-d H:i'))
                ->label('Verseny Kezdete'),
                Select::make('teams')
                ->disabledOn('edit') 
                ->required()
                ->minItems(2)
                ->relationship('teams','name')
                ->options(Team::all()->pluck('name','id'))
                ->multiple()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->label('Bajnokság neve'),
                TextColumn::make('date')
                ->label('Vereseny kezdete')
                ->dateTime('Y-m-d H:i'),
                TextColumn::make('teams.name')
                ->label('Csapatok')
                
            

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
          
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChampionships::route('/'),
            'create' => Pages\CreateChampionship::route('/create'),
            'edit' => Pages\EditChampionship::route('/{record}/edit'),
        ];
    }
}
