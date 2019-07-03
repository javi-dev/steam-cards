<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function games()
    {
        return $this->belongsToMany(Game::class);
    }

    public function getBadgesAttribute()
    {
        return $this->games->pluck('badge')->filter();
    }

    public function getGamesWithBadgesAttribute()
    {
        return $this->games->filter(function ($game) {
            return $game->badge != null;
        });
    }
}
