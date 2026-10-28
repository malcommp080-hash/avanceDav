@extends('htmlv.layout')

@section('titulo', 'Inicio')

@section('contenido')
@php
  // Tarjetas de Figma: si hay producto con foto se usa; si no, la imagen de muestra
  $tarjetas = [
    ['Oferta del día', $oferta, 'ves1.jpg', route('tienda.catalogo', ['filtro' => 'ofertas']), $oferta?->tiene_mayoreo ? $oferta->Precio_mayoreo : $oferta?->Precioneto, 'precio de mayoreo'],
    ['Menos de $100', $menos100, 'ves2.jpg', route('tienda.catalogo', ['filtro' => 'menos100']), $menos100?->Precioneto, 'desde'],
    ['Más vendidos', $masVendido, 'ves3.jpg', route('tienda.catalogo', ['filtro' => 'vendidos']), $masVendido?->Precioneto, 'el favorito'],
  ];
  $collage = $portadas->pluck('imagen_url')->filter()->values()->all() + [asset('img/ves1.jpg'), asset('img/ves2.jpg'), asset('img/ves3.jpg')];
@endphp

<section class="hero">
  <div class="hero-fila">
    <div>
      <span class="sobre"><i>Nuevo</i> Tienda en línea de Distribuidora David</span>
      <h1>Ropa para toda la familia a <em>precio de mayoreo</em></h1>
      <p>Chamarras, playeras, jeans y más para caballero, dama y niños. Arma tu pedido desde 5 piezas por modelo combinando tallas.</p>
      <div class="botones">
        <a href="{{ route('tienda.catalogo') }}" class="btn btn-primario btn-grande">Ver catálogo @include('htmlv.partials.icono', ['i' => 'flecha'])</a>
        <a href="{{ route('tienda.catalogo', ['filtro' => 'ofertas']) }}" class="btn btn-claro btn-grande">Ofertas de mayoreo</a>
      </div>
    </div>
    <div class="hero-collage" aria-hidden="true">
      <figure class="f1"><img src="{{ $collage[0] }}" alt=""></figure>
      <figure class="f2"><img src="{{ $collage[1] }}" alt=""></figure>
      <figure class="f3"><img src="{{ $collage[2] }}" alt=""></figure>
      @if($oferta?->tiene_mayoreo)<div class="precio-flota">Mayoreo desde<b>${{ number_format($oferta->Precio_mayoreo, 0) }}</b></div>@endif
    </div>
  </div>
</section>

<section class="confianza" aria-label="Beneficios">
  <ul>
    <li><span class="circ">@include('htmlv.partials.icono', ['i' => 'etiqueta'])</span><span><b>Precio de mayoreo</b>Desde 5 piezas por modelo</span></li>
    <li><span class="circ">@include('htmlv.partials.icono', ['i' => 'ropa'])</span><span><b>Combina tallas</b>CH, M, G y más en un pedido</span></li>
    <li><span class="circ">@include('htmlv.partials.icono', ['i' => 'escudo'])</span><span><b>Pago verificado</b>Depósito o transferencia</span></li>
    <li><span class="circ">@include('htmlv.partials.icono', ['i' => 'camion'])</span><span><b>Entrega o recolección</b>San Martín Texmelucan, Pue.</span></li>
  </ul>
</section>

<div class="contenedor">
  @if(session('ok') || session('err') || $errors->any())<div style="margin-top:24px">@include('htmlv.partials.avisos')</div>@endif

  <section class="bloque">
    <div class="bloque-cab">
      <div><h2>Lo destacado</h2><p>Lo más buscado de la semana</p></div>
    </div>
    <div class="destacados">
      @foreach($tarjetas as [$titulo, $p, $muestra, $url, $precio, $leyenda])
      <a class="destacado" href="{{ $url }}">
        <img src="{{ $p?->imagen_url ?? asset('img/' . $muestra) }}" alt="">
        <span class="tit">{{ $titulo }}</span>
        <span class="pie-dest">
          <span>@if($p){{ $p->Nombre }}<b>${{ rtrim(rtrim(number_format($precio, 2), '0'), '.') }}</b>@else<b>{{ $titulo }}</b>@endif</span>
          <span class="flecha">@include('htmlv.partials.icono', ['i' => 'flecha'])</span>
        </span>
      </a>
      @endforeach
    </div>
  </section>

  @if($categorias->where('productos_count', '>', 0)->isNotEmpty())
  <section class="bloque">
    <div class="bloque-cab">
      <div><h2>Compra por categoría</h2><p>Encuentra rápido lo que buscas</p></div>
      <a href="{{ route('tienda.categorias') }}">Ver todas @include('htmlv.partials.icono', ['i' => 'flecha'])</a>
    </div>
    <div class="cats">
      @foreach($categorias->where('productos_count', '>', 0) as $c)
      <a class="cat" href="{{ route('tienda.catalogo', ['categoria' => $c->Id_categoria]) }}">
        <span><b>{{ $c->Nombre }}</b><small>{{ $c->productos_count }} {{ $c->productos_count === 1 ? 'modelo' : 'modelos' }}</small></span>
        <span class="circ">@include('htmlv.partials.icono', ['i' => 'flecha'])</span>
      </a>
      @endforeach
    </div>
  </section>
  @endif

  @if($novedades->isNotEmpty())
  <section class="bloque">
    <div class="bloque-cab">
      <div><h2>Novedades</h2><p>Los modelos que acaban de llegar</p></div>
      <a href="{{ route('tienda.catalogo', ['orden' => 'nuevos']) }}">Ver todo @include('htmlv.partials.icono', ['i' => 'flecha'])</a>
    </div>
    <div class="grid-prod">
      @foreach($novedades as $p)@include('htmlv.partials.tarjeta', ['p' => $p])@endforeach
    </div>
  </section>
  @endif
</div>
@endsection
