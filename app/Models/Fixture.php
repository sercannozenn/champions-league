<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'fixtures';
    protected $fillable = ['home_club_id', 'home_club_score', 'away_club_id', 'away_club_score', 'week_number', 'match_play_status'];

    public function homeClub(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Clubs::class, 'id', 'home_club_id');
    }
    public function awayClub(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Clubs::class, 'id', 'away_club_id');
    }
}
