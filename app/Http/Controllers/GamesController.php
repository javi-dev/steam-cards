<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game;

class GamesController extends Controller
{
    public function index()
    {
        $games = auth()->user()->games->filter(function($game) {
            return $game->booster != null;
        });
        
        return view('games.index', compact('games', $games));
    }
}
