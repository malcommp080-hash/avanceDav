{{-- Campo con ícono. Parámetros: $id, $label, $ico, $tipo='text', $attrs='' (atributos extra), $ayuda, $valor --}}
@php $tipo = $tipo ?? 'text'; $esClave = $tipo === 'password'; @endphp
<div class="campo">
  <label for="{{ $id }}">{{ $label }}</label>
  <div class="entrada">
    @include('htmlv.partials.icono', ['i' => $ico])
    <input type="{{ $tipo }}" id="{{ $id }}" name="{{ $nombre ?? $id }}" value="{{ $esClave ? '' : old($nombre ?? $id, $valor ?? '') }}" {!! $attrs ?? '' !!} @if($errors->has($nombre ?? $id)) aria-invalid="true" @endif @if($esClave) style="padding-right:86px" @endif>
    @if($esClave)<button type="button" class="mostrar-clave" data-ver-clave="{{ $id }}" aria-pressed="false" aria-label="Mostrar contraseña">Mostrar</button>@endif
  </div>
  @isset($ayuda)<div class="ayuda">{{ $ayuda }}</div>@endisset
</div>
