{{-- Cabecera de página: migas de pan, título y descripción opcional.
     Parámetros: $titulo, $migas = ['Texto' => url|null, ...], $desc --}}
<section class="pag-cab">
  <div class="contenedor">
    <nav class="migas" aria-label="Ruta">
      <a href="{{ route('tienda.inicio') }}">Inicio</a>
      @foreach($migas ?? [] as $txt => $url)
        <span aria-hidden="true">/</span>
        @if($url)<a href="{{ $url }}">{{ $txt }}</a>@else<span aria-current="page">{{ $txt }}</span>@endif
      @endforeach
    </nav>
    <h1>{{ $titulo }}</h1>
    @isset($desc)<p class="desc">{{ $desc }}</p>@endisset
  </div>
</section>
