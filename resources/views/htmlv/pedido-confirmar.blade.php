@extends('htmlv.layout')

@section('titulo', 'Confirmación de pedido')

@section('contenido')
@include('htmlv.partials.barra', ['titulo' => 'Confirmación de pedido', 'migas' => ['Carrito' => route('tienda.carrito'), 'Confirmar' => null]])

<div class="contenedor seccion">
  @include('htmlv.partials.pasos', ['paso' => 1])
  @include('htmlv.partials.avisos')

  <form action="{{ route('tienda.pedido.crear') }}" method="post" class="dos-col">
    @csrf
    <div class="panel">
      <h2>¿A dónde lo entregamos?</h2>
      <div class="campo">
        <label for="direccion">Dirección de entrega</label>
        <div class="entrada">@include('htmlv.partials.icono', ['i' => 'casa'])<input id="direccion" name="direccion" maxlength="100" required value="{{ old('direccion', $cliente->Direccion) }}" autocomplete="street-address"></div>
      </div>
      <div class="campo" style="max-width:240px">
        <label for="cp">Código postal</label>
        <div class="entrada">@include('htmlv.partials.icono', ['i' => 'ubicacion'])<input id="cp" name="cp" required inputmode="numeric" pattern="[0-9]{5}" maxlength="5" value="{{ old('cp', $cliente->CP) }}" autocomplete="postal-code"></div>
      </div>
      <div class="alerta info" style="margin:6px 0 0">@include('htmlv.partials.icono', ['i' => 'info'])<span>Al confirmar, <b>apartamos tus piezas</b> y te mostramos los datos para depositar o transferir.</span></div>
    </div>

    <aside class="resumen">
      <h2>Tu pedido</h2>
      <div class="lineas">
        @foreach($renglones as $r)
        <div class="linea">
          <span class="mini">@if($r['producto']->imagen_url)<img src="{{ $r['producto']->imagen_url }}" alt="">@else @include('htmlv.partials.icono', ['i' => 'ropa']) @endif<span class="n">{{ $r['cantidad'] }}</span></span>
          <span><b>{{ $r['producto']->Nombre }}</b><small>Talla {{ $r['talla'] === 'UNI' ? 'única' : $r['talla'] }}@if($r['mayoreo']) · mayoreo @endif</small></span>
          <span class="monto">${{ number_format($r['subtotal'], 2) }}</span>
        </div>
        @endforeach
      </div>
      <div class="fila" style="margin-top:18px"><span>Piezas</span><span>{{ $piezas }}</span></div>
      <div class="total"><span>Total a pagar</span><b>${{ number_format($total, 2) }}</b></div>
      <button type="submit" class="btn btn-primario btn-bloque btn-grande">Quiero mi pedido</button>
      <a href="{{ route('tienda.carrito') }}" class="btn btn-claro btn-bloque" style="margin-top:10px">Volver al carrito</a>
    </aside>
  </form>
</div>
@endsection
