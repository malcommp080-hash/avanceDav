{{-- Tarjeta de producto del catálogo. Parámetro: $p (Producto con tallas cargadas) --}}
@php
  $disp = $p->tallas_ordenadas->where('Stock', '>', 0);
  $pocas = $p->Stock > 0 && $p->Stock <= 5;
@endphp
<a class="tarjeta" href="{{ route('tienda.producto', $p) }}">
  <div class="foto">
    @if($p->imagen_url)<img src="{{ $p->imagen_url }}" alt="{{ $p->Nombre }}" loading="lazy">
    @else<span class="sin-foto">@include('htmlv.partials.icono', ['i' => 'ropa'])</span>@endif
    <div class="etiquetas">
      @if($p->tiene_mayoreo)<span class="etq etq-mayoreo">Mayoreo</span>@endif
      @if($pocas)<span class="etq etq-pocas">Últimas piezas</span>@endif
    </div>
    <span class="ver">Ver producto @include('htmlv.partials.icono', ['i' => 'flecha'])</span>
  </div>
  <div class="info">
    <div class="meta">{{ $p->segmento_texto }}@if($p->Color) · {{ $p->Color }}@endif</div>
    <h3>{{ $p->Nombre }}</h3>
    <div class="precios">
      <span class="precio">${{ number_format($p->Precioneto, 2) }}</span>
      @if($p->tiene_mayoreo)<span class="may">${{ number_format($p->Precio_mayoreo, 2) }} desde {{ $p->Min_mayoreo }} pz</span>@endif
    </div>
    @if($disp->isNotEmpty())
    <div class="tallas-mini" aria-label="Tallas disponibles">@foreach($disp->take(6) as $t)<span>{{ $t->Talla === 'UNI' ? 'Única' : $t->Talla }}</span>@endforeach</div>
    @endif
  </div>
</a>
