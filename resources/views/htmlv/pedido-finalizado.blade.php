@extends('htmlv.layout')

@section('titulo', 'Compra finalizada')

@section('contenido')
@php
  $p = $venta->pago;
  [$clase, $icono, $titulo, $texto] = match (true) {
    $p?->Estado === 'APROBADO'  => ['ok', 'check-circ', '¡Pago aprobado!', 'Confirmamos tu pago el ' . $p->Validado_en?->format('d/m/Y') . '. Estamos preparando tu pedido.'],
    $p?->Estado === 'RECHAZADO' => ['mal', 'x-circ', 'Tu pago fue rechazado', 'Motivo: ' . $p->Motivo_rechazo . '. Las piezas se liberaron; puedes volver a hacer tu pedido.'],
    (bool) $p?->Comprobante     => ['ok', 'check-circ', '¡Gracias por tu compra!', 'Recibimos tu ticket. En breve validaremos tu pedido.'],
    default                     => ['esp', 'reloj', 'Tu pedido está apartado', 'Sube tu ticket de pago para que podamos validarlo.'],
  };
@endphp

<div class="contenedor seccion" style="padding-top:36px">
  @include('htmlv.partials.pasos', ['paso' => $p?->Comprobante ? 5 : 3])

  <div class="exito">
    <div class="circ {{ $clase }}">@include('htmlv.partials.icono', ['i' => $icono])</div>
    <h1>{{ $titulo }}</h1>
    <p>{{ $texto }}</p>

    <div class="panel">
      <dl class="dl">
        <dt>Folio</dt><dd>{{ $p?->Folio ?? '#' . $venta->Id_venta }}</dd>
        <dt>Estado</dt><dd>@if($p)<span class="etq {{ $p->Estado }}">{{ $p->estado_texto }}</span>@endif</dd>
        <dt>Dirección</dt><dd>{{ $venta->Direccion_entrega }}</dd>
        <dt>C.P.</dt><dd>{{ $venta->CP_entrega }}</dd>
        <dt>Piezas</dt><dd>{{ $venta->Total_piezas }}</dd>
        <dt>Total</dt><dd style="font-family:var(--f-titulo);font-size:20px">${{ number_format($venta->Total, 2) }}</dd>
      </dl>
      <div class="lineas" style="margin-top:20px;border-top:1px solid var(--linea);padding-top:18px">
        @foreach($venta->detalles as $d)
        <div class="linea">
          <span class="mini">@if($d->producto?->imagen_url)<img src="{{ $d->producto->imagen_url }}" alt="">@else @include('htmlv.partials.icono', ['i' => 'ropa']) @endif<span class="n">{{ $d->Cantidad }}</span></span>
          <span><b>{{ $d->producto?->Nombre ?? 'Producto' }}</b><small>Talla {{ $d->Talla === 'UNI' ? 'única' : $d->Talla }}</small></span>
          <span class="monto">${{ number_format($d->Subtotal, 2) }}</span>
        </div>
        @endforeach
      </div>
    </div>

    <div class="botones">
      @if($p?->Estado === 'PENDIENTE' && !$p->Comprobante)
        <a href="{{ route('tienda.pedido.comprobante', $venta) }}" class="btn btn-primario">@include('htmlv.partials.icono', ['i' => 'subir']) Subir ticket</a>
      @elseif($p?->Estado === 'RECHAZADO')
        <a href="{{ route('tienda.categorias') }}" class="btn btn-primario">Hacer un nuevo pedido</a>
      @endif
      <a href="{{ route('tienda.pedidos') }}" class="btn btn-claro">Ver mis pedidos</a>
    </div>
  </div>
</div>
@endsection
