<?php

namespace App\Http\Controllers;

use App\Services\ClubService;
use App\Services\FixtureService;
use Illuminate\Http\Request;

class FixtureController extends Controller
{
    protected ClubService $clubService;
    protected FixtureService $fixtureService;

    public function __construct(ClubService $clubService, FixtureService $fixtureService)
    {
        $this->clubService = $clubService;
        $this->fixtureService = $fixtureService;
    }

    public function pullFixture(): \Illuminate\Http\JsonResponse
    {
        $clubIds = $this->clubService->getAllClubsNameWithId()->pluck('id')->toArray();
        try
        {
            $fixture = $this->fixtureService->pullFixture($clubIds);
        }
        catch (\Exception $exception)
        {
            return response()
                ->json()
                ->setData($exception->getMessage())
                ->setStatusCode(500)
                ->setCharset('utf-8')
                ->header('Content-Type', 'application/json')
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        return response()
            ->json()
            ->setData($fixture)
            ->setStatusCode(200)
            ->setCharset('utf-8')
            ->header('Content-Type', 'application/json')
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    public function playNextWeek(): \Illuminate\Http\JsonResponse
    {
        $results = $this->fixtureService->playNextWeek();

        return response()
            ->json()
            ->setData($results)
            ->setStatusCode(200)
            ->setCharset('utf-8')
            ->header('Content-Type', 'application/json')
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    public function playAllWeek(): \Illuminate\Http\JsonResponse
    {
        $results = $this->fixtureService->playAllWeek();

        return response()
            ->json()
            ->setData($results)
            ->setStatusCode(200)
            ->setCharset('utf-8')
            ->header('Content-Type', 'application/json')
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    public function thisWeekFixture(): \Illuminate\Http\JsonResponse
    {
        $weekFixture = $this->fixtureService->getThisWeekFixture();

        return response()
            ->json()
            ->setData($weekFixture)
            ->setStatusCode(200)
            ->setCharset('utf-8')
            ->header('Content-Type', 'application/json')
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    public function resetData(): \Illuminate\Http\JsonResponse
    {
        $this->clubService->resetData();
        $this->fixtureService->resetData();

        return response()->json('success', 200);
    }

    public function championsPredictions()
    {
        $table = $this->clubService->getTable();
        $result = $this->fixtureService->championsPredictions($table);
    }

}
