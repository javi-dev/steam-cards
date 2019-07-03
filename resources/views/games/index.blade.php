@forelse ($games as $game)
    {{ $game->name }}
    {{ $game->booster_crafting_gems }}
@empty
    You have no games
@endforelse