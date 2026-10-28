@extends('htmlv.auth')

@section('titulo', 'Crear cuenta')
@section('subtitulo', 'Regístrate para comprar, cotizar y seguir tus pedidos.')
@section('icono', 'persona')
@section('ancho', 'ancha')

@section('contenido')
<form action="{{ route('tienda.registro.post') }}" method="post">
  @csrf
  <div class="fila-campos">
    @include('htmlv.partials.campo', ['id' => 'nombre', 'label' => 'Nombre', 'ico' => 'persona', 'attrs' => 'maxlength="20" autocomplete="given-name" required autofocus'])
    @include('htmlv.partials.campo', ['id' => 'paterno', 'label' => 'Apellido paterno', 'ico' => 'persona', 'attrs' => 'maxlength="20" autocomplete="family-name" required'])
  </div>
  @include('htmlv.partials.campo', ['id' => 'telefono', 'label' => 'Teléfono', 'ico' => 'tel', 'tipo' => 'tel', 'attrs' => 'inputmode="numeric" maxlength="10" pattern="[0-9]{10}" title="10 dígitos" autocomplete="tel" required', 'ayuda' => '10 dígitos. Lo usarás para iniciar sesión.'])
  <div class="fila-campos">
    @include('htmlv.partials.campo', ['id' => 'contrasena', 'label' => 'Contraseña', 'ico' => 'clave', 'tipo' => 'password', 'attrs' => 'minlength="6" maxlength="100" autocomplete="new-password" required', 'ayuda' => 'Mínimo 6 caracteres'])
    @include('htmlv.partials.campo', ['id' => 'confirmar', 'label' => 'Confirmar contraseña', 'ico' => 'clave', 'tipo' => 'password', 'attrs' => 'minlength="6" maxlength="100" autocomplete="new-password" required'])
  </div>
  <div class="fila-campos" style="grid-template-columns:1.6fr 1fr">
    @include('htmlv.partials.campo', ['id' => 'direccion', 'label' => 'Dirección', 'ico' => 'casa', 'attrs' => 'maxlength="100" autocomplete="street-address" required'])
    @include('htmlv.partials.campo', ['id' => 'cp', 'label' => 'Código postal', 'ico' => 'ubicacion', 'attrs' => 'inputmode="numeric" maxlength="5" pattern="[0-9]{5}" title="5 dígitos" autocomplete="postal-code" required'])
  </div>
  <button type="submit" class="btn btn-primario btn-bloque btn-grande" style="margin-top:6px">Crear mi cuenta</button>
</form>
@endsection

@section('pie')
<p class="auth-pie">¿Ya tienes cuenta? <a href="{{ route('tienda.login') }}">Inicia sesión</a></p>
@endsection
