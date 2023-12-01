<?php

namespace App\Filament\Resources\TeamResource\Pages;

use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\TeamResource;
use App\Models\Player;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateTeam extends CreateRecord
{
    protected static string $resource = TeamResource::class;

    protected function afterCreate(): void
    {
        $data = $this->data;

        $goalkeeper = Player::where('id', $data['goalkeeper_id'])->first();
        $attacker = Player::where('id', $data['attacker_id'])->first();

        $attacker->team_id = $this->record->id;
        $attacker->save();
        $goalkeeper->team_id = $this->record->id;
        $goalkeeper->save();



    }

}
