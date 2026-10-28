@extends('htmlv.auth')

@section('titulo', 'Recuperar contraseña')
@section('subtitulo', 'Escribe el teléfono y el código postal con los que te registraste.')
@section('icono', 'clave')

@section('contenido')
<form action="{{ route('tienda.recuperar.buscar') }}" method="post">
  @csrf
  @include('htmlv.partials.campo', ['id' => 'telefono', 'label' => 'Número de teléfono', 'ico' => 'tel', 'tipo' => 'tel', 'attrs' => 'inputmode="numeric" maxlength="10" placeholder="El teléfono de tu cuenta" required autofocus'])
  @include('htmlv.partials.campo', ['id' => 'cp', 'label' => 'Código postal registrado', 'ico' => 'ubicacion', 'attrs' => 'inputmode="numeric" maxlength="5" pattern="[0-9]{5}" title="5 dígitos" required', 'ayuda' => 'Lo pedimos para confirmar que la cuenta es tuya.'])
  <button type="submit" class="btn btn-primario btn-bloque btn-grande">Buscar mi cuenta</button>
</form>
@endsection

@section('pie')
<p class="auth-pie"><a href="{{ route('tienda.login') }}">← Volver al inicio de sesión</a></p>
@endsection
