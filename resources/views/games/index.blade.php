@forelse ($games as $game)
    {{ $game->name }}
    {{ $game->booster->crafting_gems }}
    {{ $game->booster->undercut_profit }}
@empty
    You have no games
@endforelse