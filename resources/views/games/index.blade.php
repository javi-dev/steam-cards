@forelse ($games as $game)
    {{ $game->name }}
@empty
    You have no games
@endforelse