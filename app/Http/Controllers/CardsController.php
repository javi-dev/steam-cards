<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Card;

class CardsController extends Controller
{
    function show($market_hash_name)
    {
        $card = Card::where('market_hash_name', rawurlencode($market_hash_name))->first();

        return view('cards.show', ['card' => $card]);
    }
}
