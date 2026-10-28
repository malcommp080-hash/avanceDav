@extends('htmlv.auth')

@section('titulo', 'Inicio de sesión')
@section('subtitulo', 'Entra con tu teléfono para comprar y ver tus pedidos.')
@section('icono', 'usuario')

@section('contenido')
<form action="{{ route('tienda.login.post') }}" method="post">
  @csrf
  @include('htmlv.partials.campo', ['id' => 'telefono', 'label' => 'Teléfono', 'ico' => 'tel', 'tipo' => 'tel', 'attrs' => 'inputmode="numeric" maxlength="10" autocomplete="tel" placeholder="10 dígitos" required autofocus'])
  @include('htmlv.partials.campo', ['id' => 'contrasena', 'label' => 'Contraseña', 'ico' => 'clave', 'tipo' => 'password', 'attrs' => 'autocomplete="current-password" required'])
  <div class="auth-fila-ext"><a href="{{ route('tienda.recuperar') }}">¿Olvidaste tu contraseña?</a></div>
  <button type="submit" class="btn btn-primario btn-bloque btn-grande">Continuar</button>
</form>
<div class="auth-sep">¿Eres nuevo?</div>
<a href="{{ route('tienda.registro') }}" class="btn btn-claro btn-bloque">Crear cuenta</a>
@endsection
