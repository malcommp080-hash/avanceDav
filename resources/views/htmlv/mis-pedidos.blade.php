@extends('htmlv.layout')

@section('titulo', 'Mis pedidos')

@section('contenido')
@include('htmlv.partials.barra', ['titulo' => 'Mis pedidos', 'migas' => ['Mis pedidos' => null], 'desc' => 'Consulta el estado de tus compras y cotizaciones.'])

<div class="contenedor seccion" style="max-width:960px">
  @include('htmlv.partials.avisos')

  <div class="pestanas" role="tablist">
    <a href="#compras" class="act" data-tab="compras" role="tab" aria-selected="true">Compras <span>{{ $pedidos->count() }}</span></a>
    <a href="#cotizaciones" data-tab="cotizaciones" role="tab" aria-selected="false">Cotizaciones <span>{{ $cotizaciones->count() }}</span></a>
  </div>

  <section id="compras" data-panel role="tabpanel">
    @forelse($pedidos as $v)
    @php $p = $v->pago; @endphp
    <div class="pedido">
      <div>
        <div class="cab"><h3>{{ $p?->Folio ?? '#' . $v->Id_venta }}</h3>@if($p)<span class="etq {{ $p->Estado }}">{{ $p->estado_texto }}</span>@endif</div>
        <div class="meta">{{ $v->fecha_texto }} · {{ $v->Total_piezas }} {{ $v->Total_piezas === 1 ? 'pieza' : 'piezas' }} · {{ $v->Direccion_entrega }}, C.P. {{ $v->CP_entrega }}</div>
        @if($p?->Estado === 'RECHAZADO')<div class="nota mal">Motivo: {{ $p->Motivo_rechazo }}. Las piezas se liberaron; puedes volver a hacer tu pedido.</div>@endif
        @if($p?->Estado === 'APROBADO')<div class="nota bien">Pago confirmado el {{ $p->Validado_en?->format('d/m/Y') }}. Preparamos tu pedido.</div>@endif
      </div>
      <div class="der">
        <b>${{ number_format($v->Total, 2) }}</b>
        <div class="botones">
          @if($p && $p->Estado === 'PENDIENTE' && !$p->Comprobante)
            <a href="{{ route('tienda.pedido.pago', $v) }}" class="btn btn-primario btn-chico">Pagar</a>
          @elseif($p && $p->Estado === 'PENDIENTE')
            <a href="{{ route('tienda.pedido.comprobante', $v) }}" class="btn btn-claro btn-chico">Cambiar ticket</a>
          @endif
          <a href="{{ route('tienda.pedido.finalizado', $v) }}" class="btn btn-claro btn-chico">Ver</a>
        </div>
      </div>
    </div>
    @empty
    <div class="vacio">
      <div class="circ">@include('htmlv.partials.icono', ['i' => 'bolsa'])</div>
      <h2>Aún no tienes compras</h2>
      <p>Cuando hagas un pedido lo verás aquí con el estado de tu pago.</p>
      <a class="btn btn-primario" href="{{ route('tienda.categorias') }}">Explorar el catálogo</a>
    </div>
    @endforelse
  </section>

  <section id="cotizaciones" data-panel role="tabpanel" hidden>
    @forelse($cotizaciones as $c)
    <div class="pedido">
      <div>
        <div class="cab"><h3>Cotización #{{ $c->Id_cotizacion }}</h3><span class="etq {{ $c->Estado }}">{{ $c->Estado === 'ATENDIDA' ? 'Atendida' : 'En espera' }}</span></div>
        <div class="meta">{{ $c->Creado_en->format('d/m/Y H:i') }} · {{ $c->detalles_count }} {{ $c->detalles_count === 1 ? 'artículo' : 'artículos' }}</div>
      </div>
      <div class="der"><b>${{ number_format($c->Total, 2) }}</b><a href="{{ route('tienda.cotizacion', $c) }}" class="btn btn-claro btn-chico">Ver</a></div>
    </div>
    @empty
    <div class="vacio">
      <div class="circ">@include('htmlv.partials.icono', ['i' => 'documento'])</div>
      <h2>Sin cotizaciones</h2>
      <p>Puedes generar una desde tu carrito sin pagar de inmediato.</p>
      <a class="btn btn-primario" href="{{ route('tienda.carrito') }}">Ir al carrito</a>
    </div>
    @endforelse
  </section>
</div>
@endsection

@push('scripts')
<script>
(function () {
  const tabs = document.querySelectorAll('[data-tab]'), paneles = document.querySelectorAll('[data-panel]');
  function mostrar(id) {
    tabs.forEach(t => { const a = t.dataset.tab === id; t.classList.toggle('act', a); t.setAttribute('aria-selected', a); });
    paneles.forEach(p => p.hidden = p.id !== id);
  }
  tabs.forEach(t => t.addEventListener('click', e => { e.preventDefault(); mostrar(t.dataset.tab); history.replaceState(null, '', '#' + t.dataset.tab); }));
  if (location.hash === '#cotizaciones') mostrar('cotizaciones');
})();
</script>
@endpush
