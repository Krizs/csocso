<?php

namespace App\Filament\Resources\ChampionshipResource\Pages;

use App\Filament\Resources\ChampionshipResource;
use App\Models\Contest;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateChampionship extends CreateRecord
{
    protected static string $resource = ChampionshipResource::class;

    protected function afterCreate(): void
    {
        $data = $this->data;

        $this->generateMatches($data['teams'],$this->record->id);

    }

    protected function generateMatches($teamIds,$champ_id)
    {
        $totalTeams = count($teamIds);
        $generatedMatches = [];

        for ($i = 0; $i < $totalTeams; $i++) {
            for ($j = $i + 1; $j < $totalTeams; $j++) {
                $match = [
                    'team_a_id' => $teamIds[$i],
                    'team_b_id' => $teamIds[$j],
                    'championship_id' => $champ_id, // Replace '1' with the actual championship ID
                ];
    
                $reverseMatch = [
                    'team_a_id' => $teamIds[$j],
                    'team_b_id' => $teamIds[$i],
                    'championship_id' => $champ_id, // Replace '1' with the actual championship ID
                ];
    
                if (!in_array($match, $generatedMatches) && !in_array($reverseMatch, $generatedMatches)) {
                    Contest::create($match);
                    $generatedMatches[] = $match;
                }
            }
        }
    }
}
