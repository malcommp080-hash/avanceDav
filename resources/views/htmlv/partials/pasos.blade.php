{{-- Pasos de la compra (Figma: Confirmación de pedido → Información de pago → Confirmación de pago → Compra finalizada). $paso = 1..4 --}}
<ol class="pasos" aria-label="Pasos de la compra">
  @foreach(['Confirmar pedido', 'Pagar', 'Enviar ticket', 'Listo'] as $i => $txt)
  <li class="{{ $i + 1 < $paso ? 'hecho' : ($i + 1 === $paso ? 'actual' : '') }}" @if($i + 1 === $paso) aria-current="step" @endif>{{ $txt }}</li>
  @endforeach
</ol>
