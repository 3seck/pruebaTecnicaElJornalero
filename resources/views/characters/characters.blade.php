@extends('layout')

@section('title', 'Personajes')

@section('content')

@if(session('success'))
    <div class="success">
        {{ session('success') }}
    </div>
@endif

@if(session('warning'))
    <div class="warning">
        {{ session('warning') }}
    </div>
@endif

<h1 class="tarjetasPersonajes">Personajes Rick y Morty</h1>

<!-- Formulario de Filtros -->
<form method="GET" action="{{ url('/characters') }}">
    <!-- Los campos de filtro -->
    <div>
        <label for="name">Nombre:</label>
        <input type="text" name="name" value="{{ request('name') }}">
    </div>

    <div>
        <label for="status">Estado:</label>
        <select name="status">
            <option value="">Cualquiera</option>
            <option value="Alive" {{ request('status') == 'Alive' ? 'selected' : '' }}>Vivo</option>
            <option value="Dead" {{ request('status') == 'Dead' ? 'selected' : '' }}>Muerto</option>
            <option value="unknown" {{ request('status') == 'unknown' ? 'selected' : '' }}>Desconocido</option>
        </select>
    </div>

    <div>
        <label for="species">Especie:</label>
        <input type="text" name="species" value="{{ request('species') }}">
    </div>

    <div>
        <label for="gender">Género:</label>
        <select name="gender">
            <option value="">Cualquiera</option>
            <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>Masculino</option>
            <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Femenino</option>
            <option value="unknown" {{ request('gender') == 'unknown' ? 'selected' : '' }}>No se sabe</option>
            <option value="Genderless" {{ request('gender') == 'Genderless' ? 'selected' : '' }}>Sin genero definido</option>
        </select>
    </div>

    <button type="submit">Aplicar</button>
</form>

<!-- Grid de Personajes -->
<ul class="character-grid">
    @foreach($characters as $character)
        <li>
            <a href="{{ url('/characters/' . $character['id']) }}" class="tarjetasPersonajes">
                {{ $character['name'] }}
                <img src="{{ $character['image'] }}" alt="{{ $character['name'] }}" class="character-image">
            </a>
        </li>
    @endforeach
</ul>

<!-- Paginación -->
@if(isset($info['prev']) || isset($info['next']))
    <div class="pagination">
        @if($info['prev'])
            <a href="{{ url('/characters') . '?' . http_build_query(array_merge(request()->all(), ['page' => request()->input('page', 1) - 1])) }}"><- Anterior</a>
        @endif

        @if($info['next'])
            <a href="{{ url('/characters') . '?' . http_build_query(array_merge(request()->all(), ['page' => request()->input('page', 1) + 1])) }}">Siguiente -></a>
        @endif
    </div>
@endif

@endsection


