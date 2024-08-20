<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Rick and Morty App')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>

    <!-- Navegación -->
    <nav>
        <div>
            @if (Auth::check())
                <a href="{{ url('/characters') }}"
                    class="{{ Request::is('characters*') ? 'active-link' : '' }}">Personajes</a>
                <a href="{{ url('/favorites') }}" class="{{ Request::is('favorites*') ? 'active-link' : '' }}">Mis
                    favoritos</a>
            @else
                <a href="{{ url('/login') }}" class="{{ Request::is('login') ? 'active-link' : '' }}">Login</a>
                <a href="{{ url('/register') }}" class="{{ Request::is('register') ? 'active-link' : '' }}">Registro</a>
            @endif
        </div>

        @if (Auth::check())
            <form method="POST" action="{{ url('/logout') }}" class="cerrarSesion">
                @csrf
                <button type="submit">Cerrar sesión</button>
            </form>
        @endif
    </nav>

    <!-- Errores -->
    <!-- Mostrar errores si los hay -->
    @if ($errors->any())
    <div class="error-messages">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <!-- Contenido Principal -->
    <div class="container">
        @yield('content')
    </div>

    <!-- Pie de Página -->
    <footer>
        <p>Prueba técnica para el Jornalero Álvaro Moreno</p>
    </footer>

</body>

</html>
