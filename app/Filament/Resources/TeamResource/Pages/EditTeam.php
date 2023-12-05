<?php

namespace App\Filament\Resources\TeamResource\Pages;

use App\Filament\Resources\TeamResource;
use App\Models\Player;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditTeam extends EditRecord
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Action::make('swapTeam')
                ->action(function(){
                    $data = $this->data;

                    $goalkeeper = Player::where('id', $data['goalkeeper_id'])->first();
                    $attacker = Player::where('id', $data['attacker_id'])->first();

                    $attacker->position = 'goalkeeper';
                    $attacker->save();
                    
                    $goalkeeper->position = 'attacker';
                    $goalkeeper->save();

                    $this->data['goalkeeper_id'] = $attacker->id;
                    $this->data['attacker_id'] =  $goalkeeper->id;
                    $this->save();
                    
                })
                ->color('success')
                ->label('Poszt csere')
        ];
    }


}
