<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\FixtureController;
use \App\Http\Controllers\ClubController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request)
{
    return $request->user();
});

Route::get('pull-fixture', [FixtureController::class, 'pullFixture']);
Route::get('clubs', [ClubController::class, 'getClubs']);
Route::get('this-week-fixture', [FixtureController::class, 'thisWeekFixture']);
Route::get('get-table', [ClubController::class, 'getTable']);
Route::get('play-next-week', [FixtureController::class, 'playNextWeek']);
Route::get('play-all-week', [FixtureController::class, 'playAllWeek']);
Route::get('reset-data', [FixtureController::class, 'resetData']);
Route::get('champions-predictions', [FixtureController::class, 'championsPredictions']);

