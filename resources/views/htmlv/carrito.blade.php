@extends('htmlv.layout')

@section('titulo', 'Mi carrito')

@section('contenido')
@include('htmlv.partials.barra', ['titulo' => 'Mi carrito', 'migas' => ['Carrito' => null], 'desc' => $renglones ? $piezas . ' ' . ($piezas === 1 ? 'pieza' : 'piezas') . ' en tu carrito' : null])

<div class="contenedor seccion">
  @include('htmlv.partials.avisos')

  @if(!$renglones)
    <div class="vacio">
      <div class="circ">@include('htmlv.partials.icono', ['i' => 'bolsa'])</div>
      <h2>Tu carrito está vacío</h2>
      <p>Explora el catálogo y agrega tus modelos favoritos.</p>
      <a class="btn btn-primario" href="{{ route('tienda.categorias') }}">Explorar categorías</a>
    </div>
  @else
  <div class="carrito">
    <section class="lista-items" aria-label="Artículos">
      @foreach($renglones as $r)
      @php $p = $r['producto']; @endphp
      <div class="item">
        <a class="mini" href="{{ route('tienda.producto', $p) }}">@if($p->imagen_url)<img src="{{ $p->imagen_url }}" alt="">@else @include('htmlv.partials.icono', ['i' => 'ropa']) @endif</a>
        <div>
          <h3><a href="{{ route('tienda.producto', $p) }}">{{ $p->Nombre }}</a></h3>
          <div class="meta">Talla {{ $r['talla'] === 'UNI' ? 'única' : $r['talla'] }}@if($p->Color) · {{ $p->Color }}@endif · ${{ number_format($r['precio'], 2) }} c/u</div>
          @if($r['falta'])<div class="falta">Sólo quedan {{ $r['stock'] }} pieza(s) en esta talla. Ajusta la cantidad.</div>@endif
          <div class="controles">
            <form action="{{ route('tienda.carrito.actualizar') }}" method="post" class="form-cant">
              @csrf @method('PATCH')
              <input type="hidden" name="clave" value="{{ $r['clave'] }}">
              <div class="cantidad chica">
                <button type="submit" name="cantidad" value="{{ $r['cantidad'] - 1 }}" aria-label="Quitar una">−</button>
                <input type="number" name="cantidad_manual" value="{{ $r['cantidad'] }}" min="0" max="{{ max($r['stock'], 1) }}" aria-label="Cantidad de {{ $p->Nombre }}">
                <button type="submit" name="cantidad" value="{{ $r['cantidad'] + 1 }}" aria-label="Agregar una" @disabled($r['cantidad'] >= $r['stock'])>+</button>
              </div>
            </form>
            <form action="{{ route('tienda.carrito.quitar') }}" method="post">
              @csrf @method('DELETE')
              <input type="hidden" name="clave" value="{{ $r['clave'] }}">
              <button type="submit" class="enlace rojo">Quitar</button>
            </form>
          </div>
        </div>
        <div class="importe">
          <b>${{ number_format($r['subtotal'], 2) }}</b>
          @if($r['mayoreo'])<div><span class="etq etq-mayoreo">Mayoreo</span></div>@endif
        </div>
      </div>
      @endforeach
    </section>

    <aside class="resumen" aria-label="Resumen">
      <h2>Resumen del pedido</h2>
      <div class="fila"><span>Piezas</span><span>{{ $piezas }}</span></div>
      @php $ahorro = collect($renglones)->sum(fn ($r) => ($r['producto']->Precioneto - $r['precio']) * $r['cantidad']); @endphp
      @if($ahorro > 0)<div class="fila" style="color:var(--mayoreo)"><span>Ahorro por mayoreo</span><span>−${{ number_format($ahorro, 2) }}</span></div>@endif
      <div class="total"><span>Total</span><b>${{ number_format($total, 2) }}</b></div>

      @if($hayFaltantes)
        <button class="btn btn-primario btn-bloque btn-grande" disabled>Continuar al pago</button>
        <p class="nota" style="color:var(--error)">Ajusta las cantidades marcadas para continuar.</p>
      @else
        <a href="{{ route('tienda.pedido.confirmar') }}" class="btn btn-primario btn-bloque btn-grande">Continuar al pago @include('htmlv.partials.icono', ['i' => 'flecha'])</a>
      @endif
      <form action="{{ route('tienda.cotizar') }}" method="post" style="margin-top:10px">@csrf
        <button type="submit" class="btn btn-claro btn-bloque">@include('htmlv.partials.icono', ['i' => 'documento']) Generar cotización</button>
      </form>
      <p class="nota">@include('htmlv.partials.icono', ['i' => 'info'])<span>¿Pedido grande o quieres apartar? La cotización llega a un colaborador que te contactará.@unless(session('cliente_id')) Para pagar o cotizar necesitas <a href="{{ route('tienda.login') }}">iniciar sesión</a>.@endunless</span></p>
    </aside>
  </div>
  @endif
</div>
@endsection

@push('scripts')
<script>
  // Al escribir una cantidad y salir del campo se guarda sola
  // (Enter también, sin que cuente como clic en el botón "−", que es el primero del formulario)
  document.querySelectorAll('.form-cant input[type=number]').forEach(i => {
    const guardar = () => { i.form.querySelectorAll('button[name=cantidad]').forEach(b => b.disabled = true); i.form.submit(); };
    i.addEventListener('change', guardar);
    i.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); guardar(); } });
  });
</script>
@endpush
