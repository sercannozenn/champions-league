<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clubs extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'clubs';
    protected $fillable = ['name', 'goalkeeper_effect', 'home_team_effect', 'fan_effect', 'weather_effect', 'striker_effect', 'winner_effect'];
}
