@extends('htmlv.layout')

@section('titulo', $producto->Nombre)

@section('contenido')
@php
  $tallas = $producto->tallas_ordenadas;
  $disponibles = $tallas->where('Stock', '>', 0);
  // Si sólo hay una talla con piezas se deja elegida
  $elegida = old('talla', $disponibles->count() === 1 ? $disponibles->first()->Talla : null);
  $colores = ['negro' => '#111827', 'blanco' => '#ffffff', 'gris' => '#9ca3af', 'azul' => '#1e4fd8', 'marino' => '#14234b', 'rojo' => '#dc2626', 'verde' => '#2e7d32', 'rosa' => '#ec6fa9', 'beige' => '#d8c3a5', 'cafe' => '#6d4c41', 'café' => '#6d4c41', 'amarillo' => '#f9c80e', 'morado' => '#7b3fa0', 'naranja' => '#f57c00', 'caqui' => '#a89f68'];
  $muestra = $colores[mb_strtolower(trim((string) $producto->Color))] ?? null;
  $cat = $producto->categoria;
@endphp

<div class="contenedor" style="padding-top:22px">
  <nav class="migas" aria-label="Ruta">
    <a href="{{ route('tienda.inicio') }}">Inicio</a><span aria-hidden="true">/</span>
    @if($cat)<a href="{{ route('tienda.catalogo', ['categoria' => $cat->Id_categoria]) }}">{{ $cat->Nombre }}</a><span aria-hidden="true">/</span>@endif
    <span aria-current="page">{{ $producto->Nombre }}</span>
  </nav>
</div>

<div class="contenedor seccion" style="padding-top:10px">
  @include('htmlv.partials.avisos')

  <article class="ficha">
    <div class="ficha-foto">
      @if($producto->imagen_url)<img src="{{ $producto->imagen_url }}" alt="{{ $producto->Nombre }}">
      @else<span class="sin-foto">@include('htmlv.partials.icono', ['i' => 'ropa'])</span>@endif
    </div>

    <form class="ficha-info" action="{{ route('tienda.carrito.agregar') }}" method="post" id="formAgregar">
      @csrf
      <input type="hidden" name="id_producto" value="{{ $producto->Id_producto }}">

      <div class="meta">{{ $cat?->Nombre }} · {{ $producto->segmento_texto }}</div>
      <h1>{{ $producto->Nombre }}</h1>
      <div class="precio">${{ number_format($producto->Precioneto, 2) }}</div>
      @if($producto->tiene_mayoreo)
      <div class="caja-mayoreo">@include('htmlv.partials.icono', ['i' => 'etiqueta'])<span><b>${{ number_format($producto->Precio_mayoreo, 2) }} c/u</b> llevando {{ $producto->Min_mayoreo }} piezas o más. Puedes combinar tallas.</span></div>
      @endif
      @if($producto->Descripcion)<p class="desc">{{ $producto->Descripcion }}</p>@endif

      <hr>

      @if($producto->Color)
      <div class="campo-tit">Color</div>
      <div class="color-fila" style="margin-bottom:22px">@if($muestra)<span class="muestra" style="background:{{ $muestra }}"></span>@endif {{ $producto->Color }}</div>
      @endif

      <div class="campo-tit" id="lblTalla">Talla <span>Toca para elegir</span></div>
      <div class="tallas" role="radiogroup" aria-labelledby="lblTalla">
        @foreach($tallas as $t)
        <input type="radio" name="talla" id="t{{ $loop->index }}" value="{{ $t->Talla }}" data-stock="{{ $t->Stock }}" @checked($elegida === $t->Talla) @disabled($t->Stock < 1)>
        <label for="t{{ $loop->index }}" title="{{ $t->Stock < 1 ? 'Agotada' : $t->Stock . ' disponibles' }}">{{ $t->Talla === 'UNI' ? 'Única' : $t->Talla }}</label>
        @endforeach
      </div>
      <div class="stock-msg" id="stock" aria-live="polite">{{ $disponibles->isEmpty() ? 'Agotado por ahora' : 'Elige tu talla para ver las piezas disponibles' }}</div>

      <div class="campo-tit" style="margin-top:22px">Cantidad</div>
      <div class="comprar">
        <div class="cantidad">
          <button type="button" data-paso="-1" aria-label="Quitar una">−</button>
          <input type="number" name="cantidad" id="cantidad" value="{{ old('cantidad', 1) }}" min="1" max="{{ $disponibles->max('Stock') ?: 1 }}" inputmode="numeric" aria-label="Cantidad">
          <button type="button" data-paso="1" aria-label="Agregar una">+</button>
        </div>
        <button type="submit" class="btn btn-primario" @disabled($disponibles->isEmpty())>@include('htmlv.partials.icono', ['i' => 'bolsa']) Agregar al carrito</button>
      </div>

      <ul class="beneficios">
        <li>@include('htmlv.partials.icono', ['i' => 'escudo'])<span><b>Pago verificado.</b> Depositas o transfieres, subes tu ticket y lo validamos.</span></li>
        <li>@include('htmlv.partials.icono', ['i' => 'camion'])<span><b>Entrega o recolección</b> en San Martín Texmelucan, Puebla.</span></li>
        <li>@include('htmlv.partials.icono', ['i' => 'documento'])<span><b>¿Pedido grande?</b> Genera una cotización desde tu carrito y te contactamos.</span></li>
      </ul>
    </form>
  </article>

  @if($relacionados->isNotEmpty())
  <section class="bloque">
    <div class="bloque-cab"><div><h2>También te puede gustar</h2></div></div>
    <div class="grid-prod">
      @foreach($relacionados as $p)@include('htmlv.partials.tarjeta', ['p' => $p])@endforeach
    </div>
  </section>
  @endif
</div>
@endsection

@push('scripts')
<script>
(function () {
  const form = document.getElementById('formAgregar');
  const cant = document.getElementById('cantidad');
  const stock = document.getElementById('stock');
  const menos = form.querySelector('[data-paso="-1"]'), mas = form.querySelector('[data-paso="1"]');

  function limites() {
    const v = parseInt(cant.value, 10) || 1, max = parseInt(cant.max, 10) || 999;
    menos.disabled = v <= 1; mas.disabled = v >= max;
  }
  function actualizar() {
    const t = form.querySelector('input[name="talla"]:checked');
    if (!t) return limites();
    const s = parseInt(t.dataset.stock, 10);
    cant.max = s;
    if (parseInt(cant.value, 10) > s) cant.value = s;
    stock.className = 'stock-msg ' + (s <= 5 ? 'pocas' : 'hay');
    stock.textContent = s <= 5 ? `¡Quedan sólo ${s} ${s === 1 ? 'pieza' : 'piezas'} en esta talla!` : `${s} piezas disponibles`;
    limites();
  }
  form.querySelectorAll('input[name="talla"]').forEach(r => r.addEventListener('change', actualizar));
  form.querySelectorAll('[data-paso]').forEach(b => b.addEventListener('click', () => {
    const v = (parseInt(cant.value, 10) || 1) + parseInt(b.dataset.paso, 10);
    cant.value = Math.max(1, Math.min(parseInt(cant.max, 10) || 999, v));
    limites();
  }));
  cant.addEventListener('input', limites);
  form.addEventListener('submit', e => {
    if (!form.querySelector('input[name="talla"]:checked')) {
      e.preventDefault();
      stock.className = 'stock-msg error';
      stock.textContent = 'Elige una talla antes de agregar al carrito.';
      document.getElementById('lblTalla').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  });
  actualizar();
})();
</script>
@endpush
