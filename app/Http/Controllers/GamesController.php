<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GamesController extends Controller
{
    public function index()
    {
        $games = auth()->user()->gamesWithBadges;

        return view('games.index', compact('games'));
    }
}
