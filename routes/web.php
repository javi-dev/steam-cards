<?php

Route::get('cards/{market_hash_name}', 'CardsController@show');
Route::get('/{user_name}/games/crafting', 'GamesController@index')->middleware('auth');

Route::put('/booster/{booster}/offers', 'BoosterOffersController@update');

Auth::routes();
