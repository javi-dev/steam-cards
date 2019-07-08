@forelse ($games as $game)
    {{ $game->name }}
    {{ $game->booster_crafting_gems }}
    {{ $game->booster->undercut_profit }}
@empty
    You have no games
@endforelse