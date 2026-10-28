@extends('htmlv.layout')

@section('titulo', 'Información de pago')

@section('contenido')
@include('htmlv.partials.barra', ['titulo' => 'Información de pago', 'migas' => ['Mis pedidos' => route('tienda.pedidos'), $venta->pago->Folio => null]])

<div class="contenedor seccion">
  @include('htmlv.partials.pasos', ['paso' => 2])
  @include('htmlv.partials.avisos')

  <div class="dos-col">
    <div class="panel">
      <h2>Deposita o transfiere a esta cuenta</h2>
      <div class="datos-banco">
        @foreach(['Banco' => $banco['banco'], 'A nombre de' => $banco['titular'], 'Cuenta (depósito en efectivo)' => $banco['cuenta'], 'CLABE (transferencia)' => $banco['clabe']] as $etq => $valor)
        <div class="dato-banco">
          <div><span>{{ $etq }}</span><b>{{ $valor }}</b></div>
          @if(str_contains($etq, 'Cuenta') || str_contains($etq, 'CLABE'))<button type="button" class="copiar" data-copiar="{{ preg_replace('/\s+/', '', $valor) }}">Copiar</button>@endif
        </div>
        @endforeach
      </div>
      <div class="alerta info" style="margin:18px 0 0">@include('htmlv.partials.icono', ['i' => 'info'])<span>Escribe el folio <b>{{ $venta->pago->Folio }}</b> como concepto o referencia. Tus piezas quedan apartadas mientras validamos tu pago.</span></div>
    </div>

    <aside class="resumen">
      <div class="monto-grande"><span>Total a pagar</span><b>${{ number_format($venta->Total, 2) }}</b><small>Folio {{ $venta->pago->Folio }}</small></div>
      <a href="{{ route('tienda.pedido.comprobante', $venta) }}" class="btn btn-primario btn-bloque btn-grande">@include('htmlv.partials.icono', ['i' => 'subir']) Ya pagué, subir ticket</a>
      <form action="{{ route('tienda.pedido.cancelar', $venta) }}" method="post" onsubmit="return confirm('¿Cancelar este pedido? Las piezas se liberarán.')" style="margin-top:10px">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-peligro btn-bloque">Cancelar pedido</button>
      </form>
      <p class="nota">@include('htmlv.partials.icono', ['i' => 'reloj'])<span>Puedes subir tu ticket después desde <a href="{{ route('tienda.pedidos') }}">Mis pedidos</a>.</span></p>
    </aside>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.querySelectorAll('[data-copiar]').forEach(b => b.addEventListener('click', async () => {
    try { await navigator.clipboard.writeText(b.dataset.copiar); b.textContent = '¡Copiado!'; }
    catch (e) { b.textContent = 'Selecciónalo'; }
    setTimeout(() => b.textContent = 'Copiar', 1800);
  }));
</script>
@endpush
