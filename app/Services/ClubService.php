<?php

namespace App\Services;

use App\Models\Clubs;
use Illuminate\Support\Facades\Artisan;

class ClubService
{
    protected Clubs $clubs;

    public function __construct(Clubs $clubs)
    {
        $this->clubs = $clubs;
    }

    public function getAllClubsNameWithId(string $orderBy = 'ASC'): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->clubs::query()
            ->select('id', 'name')
            ->orderBy('name', $orderBy)
            ->get();
    }

    public function getTable(): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->clubs::query()
            ->select([
                'name', 'p', 'w', 'd', 'l', 'gd'
            ])
            ->orderBy('p', 'DESC')
            ->orderBy('gd', 'DESC')
            ->orderBy('name', 'ASC')
            ->get();
    }

    public function updateWinner(int $winnerClubId, int $loserClubId, int $gd = 0)
    {
        $winnerClub = $this->findById($winnerClubId);
        $winnerClub->winner_effect += 3;
        $winnerClub->p += 3;
        $winnerClub->gd += $gd;
        $winnerClub->w ++;
        $winnerClub->save();

        $loserClub = $this->findById($loserClubId);
        $loserClub->gd -= $gd;
        $loserClub->l ++;
        $loserClub->winner_effect -= 3;
        $loserClub->save();

    }

    public function updateDraw(int $homeId, int $awayId)
    {
        $winnerClub = $this->findById($homeId);
        $winnerClub->d ++;
        $winnerClub->p ++;
        $winnerClub->winner_effect -= 1;
        $winnerClub->save();

        $loserClub = $this->findById($awayId);
        $loserClub->d ++;
        $loserClub->p ++;
        $loserClub->winner_effect -= 1;
        $loserClub->save();

    }

    public function findById(int $id)
    {
        return $this->clubs::find($id);
    }

    public function resetData()
    {
        $this->clubs::truncate();
        Artisan::call('db:seed --class=ClubSeeder');
    }
}
