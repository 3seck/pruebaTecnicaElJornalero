<!-- resources/views/favorites.blade.php -->
@extends('layout')

@section('title', 'Favoritos')

@section('content')
    <h1 class="tarjetasPersonajes">Mis favoritos</h1>

    <ul class="character-grid">
        @foreach ($characters as $character)
            <li>
                <h2 class="tarjetasPersonajes">{{ $character['name'] }}</h2>
                <a href="{{ url('/characters/' . $character['id']) }}" >
                    <img src="{{ $character['image'] }}" alt="{{ $character['name'] }}">
                </a>
                <p>Status: {{ $character['status'] }}</p>
                <p>Species: {{ $character['species'] }}</p>

                <form method="POST" action="{{ url('/favorites/' . $character['favorite_id']) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Eliminar</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
