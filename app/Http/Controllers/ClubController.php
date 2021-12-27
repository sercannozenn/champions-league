<?php

namespace App\Http\Controllers;

use App\Services\ClubService;

class ClubController extends Controller
{
    protected ClubService $clubService;

    public function __construct(ClubService $clubService)
    {
        $this->clubService = $clubService;
    }

    public function getClubs(): \Illuminate\Http\JsonResponse
    {
        $clubs = $this->clubService->getAllClubsNameWithId();

        return response()
            ->json()
            ->setData($clubs)
            ->setStatusCode(200)
            ->setCharset('utf-8')
            ->header('Content-Type', 'application/json')
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    public function getTable(): \Illuminate\Http\JsonResponse
    {
        $table = $this->clubService->getTable();

        return response()
            ->json()
            ->setData($table)
            ->setStatusCode(200)
            ->setCharset('utf-8')
            ->header('Content-Type', 'application/json')
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
