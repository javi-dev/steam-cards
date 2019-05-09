@forelse ($games as $game)
    {{ $game->name }}
    {{ $game->booster->crafting_gems }}
@empty
    You have no games
@endforelse