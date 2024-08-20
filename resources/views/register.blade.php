<!-- resources/views/register.blade.php -->
@extends('layout')

@section('title', 'Register')

@section('content')
<h1 class="tarjetasPersonajes">Registro</h1>

<form method="POST" action="{{ url('/register-api') }}" class="registro">

    @csrf
    
    <label for="name">Nombre: <input type="text" name="name" class="input-fijo" required></label>
    

    <label for="email">Email: <input type="email" name="email" class="input-fijo" required></label>
    

    <label for="password">Contraseña: <input type="password" name="password" class="input-fijo" required></label>
    

    <label for="password_confirmation">Confirma la contraseña: <input type="password" name="password_confirmation" class="input-fijo" required></label>
    

    <button type="submit">Registrate</button>
</form>

@endsection
