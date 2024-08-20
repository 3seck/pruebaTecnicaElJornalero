<!-- resources/views/login.blade.php -->
@extends('layout')

@section('title', 'Login')

@section('content')
<h1 class="tarjetasPersonajes">Login</h1>

<form method="POST" action="{{ url('/login-api') }}" class="registro">
    @csrf
    <label for="email">Email:<input type="email" name="email" required class="input-fijo"></label>
    

    <label for="password">Contraseña:<input type="password" name="password" required class="input-fijo"></label>
    

    <button type="submit">Login</button>
</form>
@endsection
