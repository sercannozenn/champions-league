<?php

namespace Database\Seeders;

use App\Models\Clubs;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clubs = [
            [
                'name' => 'Arsenal',
                'goalkeeper_effect' => 15,
                'home_team_effect' => 10,
                'striker_effect' => 15,
                'winner_effect' => 10
            ],
            [
                'name' => 'Chelsea',
                'goalkeeper_effect' => 20,
                'home_team_effect' => 10,
                'striker_effect' => 25,
                'winner_effect' => 25
            ],
            [
                'name' => 'Liverpool',
                'goalkeeper_effect' => 20,
                'home_team_effect' => 20,
                'striker_effect' => 30,
                'winner_effect' => 25
            ],
            [
                'name' => 'Manchester City',
                'goalkeeper_effect' => 15,
                'home_team_effect' => 15,
                'striker_effect' => 40,
                'winner_effect' => 35
            ]
        ];

        if (DB::table('clubs')->count() < 1)
        {
            DB::table('clubs')->insert($clubs);
        }
    }
}
