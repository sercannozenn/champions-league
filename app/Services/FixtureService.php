<?php

namespace App\Services;

use App\Models\Fixture;
use Illuminate\Support\Facades\Log;

class FixtureService
{
    protected Fixture $fixture;

    public function __construct(Fixture $fixture)
    {
        $this->fixture = $fixture;
    }

    /**
     * @param array $clubIds
     * @return array
     * @throws \Exception
     */
    public function pullFixture(array $clubIds): \Illuminate\Database\Eloquent\Collection|array
    {
        $drawingLotsResult = $this->drawingLots($clubIds);
        $fixture = $this->fixtureDetermination($clubIds, $drawingLotsResult);

        $this->insertFixture($fixture);

        return $this->prepareFixtures();
    }

    /**
     * Kura Çekilişi
     * @param array $clubIds
     * @return array
     *
     */
    public function drawingLots(array $clubIds): array
    {
        $sortClubs = [];

        $clubCount = count($clubIds);
        $clubCountLoop = count($clubIds);

        for ($i = 0; $i < $clubCountLoop; $i++)
        {
            $random = rand(0, $clubCount - 1);
            $sortClubs[$i] = $clubIds[$random];
            array_splice($clubIds, $random, 1);
            $clubCount--;
        }

        return $sortClubs;
    }

    /**
     * Fixtürün belirlenmesi
     * @param array $clubIds
     * @param $drawingLotsResult
     * @return array
     */

    public function fixtureDetermination(array $clubIds, $drawingLotsResult): array
    {
        $fixture = [];
        $clubCountLoop = count($clubIds);

        /**
         * Rövanşlı olacağı için x2
         */
        for ($i = 1; $i < ($clubCountLoop * 2) - 1; $i++)
        {
            /**
             * Haftaya herhangibir eleman eklenmişse yeniden sıralanacak (fikstür döndürülecek)
             * 1. takım sabit tutularak diğer takımlar sırasıyla dönecek.
             * 1-4 | 2-3
             * 3-1 | 4-2
             * 1-2 | 3-4 şeklinde tff ve normal şampiyonlar ligi kuralına göre fikstür belirlendi.
             */
            if (count($fixture))
            {
                $drawingLotsResult = $this->rotateFixture($drawingLotsResult);
            }
            /**
             * $i nin modunun alınması tekli haftalarda ev sahibi ilk takım olacak
             * çiftli haftalarda ilk takım deplasman olacak. İlk takımı sabit tutarak diğer takımları
             * döndürdüğümüz için bir içeride bir deplasmanda durumunu sağlamak adına bu şekilde ayarlandı.
             */
            if ($i % 2 == 0)
            {
                $fixture[] = [
                    'week_number' => $i,
                    'home_club_id' => $drawingLotsResult[3],
                    'away_club_id' => $drawingLotsResult[0]
                ];
            }
            else
            {
                $fixture[] = [
                    'week_number' => $i,
                    'home_club_id' => $drawingLotsResult[0],
                    'away_club_id' => $drawingLotsResult[3]
                ];
            }

            /**
             * Sezonun ilk yarısı bittikten sonra
             * ev sahibi ve deplasman takımlarının yer değiştirmesi sağlandı.
             */
            if ($i < $clubCountLoop)
            {
                $fixture[] = [
                    'week_number' => $i,
                    'home_club_id' => $drawingLotsResult[1],
                    'away_club_id' => $drawingLotsResult[2]
                ];
            }
            else
            {
                $fixture[] = [
                    'week_number' => $i,
                    'home_club_id' => $drawingLotsResult[2],
                    'away_club_id' => $drawingLotsResult[1]
                ];
            }
        }
        return $fixture;
    }

    /**
     * Fikstürün döndürülmesi
     * @param array $clubIds
     * @return array
     */
    public function rotateFixture(array $clubIds): array
    {
        $sortClubs[] = $clubIds[0];
        $sortClubs[] = $clubIds[count($clubIds) - 1];
        for ($i = 1; $i < count($clubIds) - 1; $i++)
        {
            $sortClubs[] = $clubIds[$i];
        }
        return $sortClubs;
    }

    /**
     * Fixtürün oluşturulması - create/insert edilmesi
     * @param array $fixture
     * @return void
     * @throws \Exception
     */
    public function insertFixture(array $fixture): void
    {
        try
        {
            $this->fixture::truncate();
            $this->fixture::insert($fixture);
        }
        catch (\Exception $exception)
        {
            Log::error('Fixture Insert Error Message: ' . $exception->getMessage());
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * Bütün fixturleri getirir.
     * @return array|\Illuminate\Database\Eloquent\Collection
     */
    public function getAllFixture(): array|\Illuminate\Database\Eloquent\Collection
    {
        return $this->fixture::with(['homeClub', 'awayClub'])->get();
    }

    /**
     * Fixtürün frontende gönderilmeden önce haftalara ayrılması.
     * @return array
     */
    public function prepareFixtures(): array
    {
        $fixtures = $this->getAllFixture();
        $fixtureResult = [];
        foreach ($fixtures as $fixture)
        {
            $fixtureResult[$fixture->week_number][] = $fixture;
        }
        return $fixtureResult;
    }

    /**
     * Önümüzdeki Haftanın Fixtürünü döndürür.
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function getThisWeekFixture(): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->fixture::query()
            ->with(['homeClub', 'awayClub'])
            ->where('match_play_status', 0)
            ->limit(2)
            ->get();
    }

    /**
     * Önümüzdeki haftanın maçı oynanır.
     * @return array
     */
    public function playNextWeek(): array
    {
        $weekFixture = $this->getThisWeekFixture();
        $playedGames = [];
        foreach ($weekFixture as $match)
        {
            $goal = $this->playMatch($match);
            $match->match_play_status = 1;
            $match->home_club_score = $goal['home'];
            $match->away_club_score = $goal['away'];
            $match->save();

            $playedGames[] = $match;
        }
        return $playedGames;
    }

    /**
     * Kalan maçların hepsi oynanır.
     * @return array
     */
    public function playAllWeek(): array
    {
        $remainingMatches = $this->fixture::query()
            ->where('match_play_status', 0)
            ->count();
        $remainingWeek = $remainingMatches / 2;
        $playedGames = [];

        for ($i = 0; $i < $remainingWeek; $i++)
        {
            $playedGames[] = $this->playNextWeek();
        }

        return $playedGames;
    }

    /**
     * Maçların oynanmasını sağlar.
     * @param Fixture $match
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function playMatch(Fixture $match): array
    {
        $strikerEffect = 0.4;
        $goalkeeperEffect = 0.2;
        $homeEffect = 0.2;
        $winnerEffect = 0.2;

        $strikerEffect2 = 0.2;
        $goalkeeperEffect2 = 0.6;
        $homeEffect2 = 0.1;
        $winnerEffect2 = 0.1;

        $goalsWeight = [];
        $goalsWeightCount = ['home' => 0, 'away' => 0];

        for ($i = 0; $i <= 10; $i++)
        {
            if ($i == 0)
            {
                $goalPossibility = $this->calculateGoalWeight($match, true, $strikerEffect2, $goalkeeperEffect2, $homeEffect2, $winnerEffect2);
            }
            else
            {
                $goalPossibility = $this->calculateGoalWeight($match, false, $strikerEffect, $goalkeeperEffect, $homeEffect, $winnerEffect);
            }
            $goalsWeight['away'][$i] = max($goalPossibility['away'], 0);
            $goalsWeightCount['away'] += max($goalPossibility['away'], 0);

            $goalsWeight['home'][$i] = max($goalPossibility['home'], 0);
            $goalsWeightCount['home'] += max($goalPossibility['home'], 0);
            $strikerEffect -= 0.06;
            $goalkeeperEffect += 0.05;
            $homeEffect += 0.01;
        }

        $goal['away'] = $this->setScore($goalsWeightCount['away'], $goalsWeight['away']);
        $goal['home'] = $this->setScore($goalsWeightCount['home'], $goalsWeight['home']);
        $clubService = app()->make(ClubService::class);

        if ($goal['away'] != $goal['home'])
        {
            if ($goal['away'] > $goal['home'])
            {
                $winnerClubId = $match->awayClub->id;
                $loserClubId = $match->homeClub->id;
                $gd = $goal['away'] - $goal['home'];
            }
            elseif ($goal['away'] < $goal['home'])
            {
                $winnerClubId = $match->homeClub->id;
                $loserClubId = $match->awayClub->id;
                $gd = $goal['home'] - $goal['away'];
            }
            $clubService->updateWinner($winnerClubId, $loserClubId, $gd);
        }
        else
        {
            $clubService->updateDraw($match->homeClub->id, $match->awayClub->id);
        }

        return $goal;
    }

    /**
     * @param Fixture $match
     * @return float[]|int[]
     */
    public function calculateGoalWeight(Fixture $match, bool $zero, float $strikerEffect, float $goalkeeperEffect, float $homeEffect, float $winnerEffect): array
    {
        $away = ($match->awayClub->striker_effect * $strikerEffect) - ($match->homeClub->goalkeeper_effect * $goalkeeperEffect) + ($match->homeClub->winner_effect * $winnerEffect);
        $home = ($match->homeClub->striker_effect * $strikerEffect) - ($match->awayClub->goalkeeper_effect * $goalkeeperEffect) + ($match->homeClub->home_effect * $homeEffect) + ($match->homeClub->winner_effect * $winnerEffect);

        if ($zero)
        {
            $away = -(($match->awayClub->striker_effect * $strikerEffect) + ($match->awayClub->winner_effect * $winnerEffect)) + ($match->homeClub->goalkeeper_effect * $goalkeeperEffect);
            $home = -(($match->homeClub->striker_effect * $strikerEffect) + ($match->homeClub->home_effect * $homeEffect) + ($match->homeClub->winner_effect * $winnerEffect)) + ($match->awayClub->goalkeeper_effect * $goalkeeperEffect);
        }

        return array('away' => $away, 'home' => $home);
    }

    /**
     * Skorun belirlenmesi
     * @param $goalsWeightCount
     * @param $goalsWeight
     * @return int|string|void
     */
    public function setScore($goalsWeightCount, $goalsWeight)
    {
        $rand = mt_rand(1, $goalsWeightCount);

        foreach ($goalsWeight as $key => $value)
        {
            $rand -= $value;
            if ($rand <= 0)
            {
                return $key;
            }
        }
    }

    /**
     * Tüm sonuçların sıfırlanması
     * @return void
     */
    public function resetData()
    {
        $this->fixture::query()
            ->where('id', '>', 0)
            ->update([
                'home_club_score' => 0,
                'away_club_score' => 0,
                'match_play_status' => 0
            ]);
    }

    public function championsPredictions($table)
    {
        $firstTeamPoint = $table[0]['p'];
        $championsList = [$table[0]];
        for ($i = 1; $i < count($table); $i++)
        {
            $diff = $firstTeamPoint - $table[$i]['p'];
            if ($diff < 7)
            {
                $championsList[] = $table[$i];
            }
        }
        if (count($championsList) < 2)
        {
            return [1 => ['name' => $championsList[0]->name, 'percent' => 100]];
        }
        dd($championsList);
    }

}
