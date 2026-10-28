@extends('htmlv.auth')

@section('titulo', 'Nueva contraseña')
@section('subtitulo', 'Cuenta encontrada: ' . $cliente->Nombre . '. Elige una contraseña nueva.')
@section('icono', 'clave')

@section('contenido')
<form action="{{ route('tienda.recuperar.guardar') }}" method="post">
  @csrf
  @include('htmlv.partials.campo', ['id' => 'nueva', 'label' => 'Nueva contraseña', 'ico' => 'clave', 'tipo' => 'password', 'attrs' => 'minlength="6" maxlength="100" autocomplete="new-password" required autofocus', 'ayuda' => 'Mínimo 6 caracteres'])
  @include('htmlv.partials.campo', ['id' => 'confirmar', 'label' => 'Confirma tu contraseña', 'ico' => 'clave', 'tipo' => 'password', 'attrs' => 'minlength="6" maxlength="100" autocomplete="new-password" required'])
  <button type="submit" class="btn btn-primario btn-bloque btn-grande">Guardar contraseña</button>
</form>
@endsection

@section('pie')
<p class="auth-pie"><a href="{{ route('tienda.recuperar') }}">← Volver</a></p>
@endsection
