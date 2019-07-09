@forelse ($games as $game)
    {{ $game->name }}
    {{ $game->booster->crafting_gems }}
    {{ $game->booster->undercut_profit_euro }}
@empty
    You have no games
@endforelse