@extends('htmlv.layout')

@section('titulo', 'Confirmación de pago')

@section('contenido')
@include('htmlv.partials.barra', ['titulo' => 'Confirmación de pago', 'migas' => ['Mis pedidos' => route('tienda.pedidos'), $venta->pago->Folio => route('tienda.pedido.pago', $venta), 'Ticket' => null]])

<div class="contenedor seccion">
  @include('htmlv.partials.pasos', ['paso' => 3])
  @include('htmlv.partials.avisos')

  <form action="{{ route('tienda.pedido.comprobante.subir', $venta) }}" method="post" enctype="multipart/form-data" id="formTicket" class="dos-col">
    @csrf
    <label class="subir" id="zona" for="ticket">
      <input type="file" name="ticket" id="ticket" accept="image/jpeg,image/png,image/webp,application/pdf" required>
      <div class="vista" id="vista">@if($venta->pago->comprobante_url)<img src="{{ $venta->pago->comprobante_url }}" alt="Ticket enviado">@endif</div>
      <span class="circ" id="icoSubir">@include('htmlv.partials.icono', ['i' => 'subir'])</span>
      <b id="ttlSubir">Sube tu ticket de pago</b>
      <small>Toca para elegir una foto o PDF (máx. 5 MB), o arrástralo aquí</small>
    </label>

    <aside class="resumen">
      <h2>Datos del pago</h2>
      <dl class="dl" style="margin-bottom:20px">
        <dt>Folio</dt><dd>{{ $venta->pago->Folio }}</dd>
        <dt>Total</dt><dd>${{ number_format($venta->Total, 2) }}</dd>
      </dl>
      <div class="campo">
        <label for="referencia">Referencia o núm. de operación <span style="font-weight:400;color:var(--tenue)">(opcional)</span></label>
        <div class="entrada sin-ico"><input id="referencia" name="referencia" maxlength="100" value="{{ old('referencia', $venta->pago->Referencia) }}"></div>
      </div>
      <button type="submit" class="btn btn-primario btn-bloque btn-grande">@include('htmlv.partials.icono', ['i' => 'check']) Enviar pago</button>
      <a href="{{ route('tienda.pedido.pago', $venta) }}" class="btn btn-claro btn-bloque" style="margin-top:10px">Volver</a>
    </aside>
  </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
  const inp = document.getElementById('ticket'), zona = document.getElementById('zona'), vista = document.getElementById('vista'), ttl = document.getElementById('ttlSubir'), ico = document.getElementById('icoSubir');
  function mostrar(f) {
    if (!f) return;
    ttl.textContent = f.name;
    ico.style.display = 'none';
    vista.innerHTML = f.type.startsWith('image/') ? `<img src="${URL.createObjectURL(f)}" alt="Vista previa">` : '<div style="font-size:56px">📄</div>';
  }
  inp.addEventListener('change', () => mostrar(inp.files[0]));
  ['dragenter', 'dragover'].forEach(ev => zona.addEventListener(ev, e => { e.preventDefault(); zona.classList.add('arrastrando'); }));
  ['dragleave', 'drop'].forEach(ev => zona.addEventListener(ev, e => { e.preventDefault(); zona.classList.remove('arrastrando'); }));
  zona.addEventListener('drop', e => { if (e.dataTransfer.files.length) { inp.files = e.dataTransfer.files; mostrar(inp.files[0]); } });
  if (vista.querySelector('img')) ico.style.display = 'none';
})();
</script>
@endpush
