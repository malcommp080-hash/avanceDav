@extends('htmlv.auth')

@section('titulo', '¡Tu cuenta está lista!')
@section('icono', 'check-circ')

@section('contenido')
<div class="auth-exito">
  <p class="sub" style="margin-top:0">Ya puedes iniciar sesión con tu teléfono y contraseña.</p>
  <a href="{{ route('tienda.login') }}" class="btn btn-primario btn-bloque btn-grande">Iniciar sesión @include('htmlv.partials.icono', ['i' => 'flecha'])</a>
</div>
@endsection
