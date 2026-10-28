@extends('htmlv.layout')

@section('titulo', 'Cotización #' . $cot->Id_cotizacion)

@section('contenido')
@include('htmlv.partials.barra', ['titulo' => 'Cotización #' . $cot->Id_cotizacion, 'migas' => ['Mis pedidos' => route('tienda.pedidos') . '#cotizaciones', 'Cotización' => null], 'desc' => 'Generada el ' . $cot->Creado_en->format('d/m/Y \a \l\a\s H:i')])

<div class="contenedor seccion">
  @include('htmlv.partials.avisos')

  <div class="carrito">
    <section class="lista-items">
      @foreach($cot->detalles as $d)
      <div class="item">
        <span class="mini">@if($d->producto?->imagen_url)<img src="{{ $d->producto->imagen_url }}" alt="">@else @include('htmlv.partials.icono', ['i' => 'ropa']) @endif</span>
        <div>
          <h3>{{ $d->producto?->Nombre ?? 'Producto' }}</h3>
          <div class="meta">Talla {{ $d->Talla === 'UNI' ? 'única' : $d->Talla }} · {{ $d->Cantidad }} × ${{ number_format($d->Precio_unitario, 2) }}</div>
        </div>
        <div class="importe"><b>${{ number_format($d->Subtotal, 2) }}</b></div>
      </div>
      @endforeach
    </section>

    <aside class="resumen">
      <h2>Resumen</h2>
      <div class="fila"><span>Estado</span><span class="etq {{ $cot->Estado }}">{{ $cot->Estado === 'ATENDIDA' ? 'Atendida' : 'En espera' }}</span></div>
      <div class="total"><span>Total cotizado</span><b>${{ number_format($cot->Total, 2) }}</b></div>
      <p class="nota" style="margin-top:0">@include('htmlv.partials.icono', ['i' => 'info'])<span>
        @if($cot->Estado === 'ATENDIDA') Un colaborador atendió tu cotización el {{ $cot->Atendida_en?->format('d/m/Y') }}.
        @else Un colaborador te contactará al teléfono de tu cuenta. Los precios pueden cambiar según existencias. @endif
      </span></p>
      <a href="{{ route('tienda.carrito') }}" class="btn btn-primario btn-bloque" style="margin-top:18px">Ir a mi carrito</a>
    </aside>
  </div>
</div>
@endsection
