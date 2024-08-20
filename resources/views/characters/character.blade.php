@extends('layout')

@section('title', 'Detalles')

@section('content')
<div class="character-details">
    <h1 class="tarjetasPersonajes">{{ $character['name'] }}</h1>

    <div class="character-image">
        <img src="{{ $character['image'] }}" alt="{{ $character['name'] }}">
    </div>

    <div class="character-info">
        <p><strong>Estado:</strong> {{ $character['status'] }}</p>
        <p><strong>Especie:</strong> {{ $character['species'] }}</p>
    </div>

    <div class="character-actions">
        @if ($isFavorite)
            <!-- Mostrar el botón de eliminar de favoritos -->
            <form method="POST" action="{{ url('/favorites/' . $isFavorite->id) }}">
                @csrf
                @method('DELETE')
                <p class="warning">Este personaje ya esta agregado a favoritos</p>
                <button type="submit">Eliminar de favoritos</button>
            </form>
        @else
            <!-- Mostrar el botón de añadir a favoritos -->
            <form method="POST" action="{{ url('/favorites') }}">
                @csrf
                <input type="hidden" name="character_id" value="{{ $character['id'] }}">
                <button type="submit">Añadir a favoritos</button>
            </form>
        @endif
    </div>
</div>
@endsection
