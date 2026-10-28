@extends('htmlv.layout')

@section('titulo', $titulo)

@section('contenido')
@php
  // Conserva los demás filtros al cambiar de segmento u orden
  $base = array_filter(['categoria' => $filtros['categoria'] ?? null, 'q' => $filtros['q'] ?? null, 'filtro' => $filtros['filtro'] ?? null, 'orden' => $filtros['orden'] ?? null]);
  $actual = $filtros['segmento'] ?? null;
  $migas = $categoria ? ['Categorías' => route('tienda.categorias'), $categoria->Nombre => null] : [$titulo => null];
  $ordenes = ['' => 'Recomendado', 'nuevos' => 'Más nuevos', 'precio_asc' => 'Precio: menor a mayor', 'precio_desc' => 'Precio: mayor a menor', 'nombre' => 'Nombre'];
@endphp

@include('htmlv.partials.barra', ['titulo' => $titulo, 'migas' => $migas])

<div class="contenedor seccion">
  @include('htmlv.partials.avisos')

  <div class="herramientas">
    @if($segmentos->isNotEmpty())
    <nav class="segmentos" aria-label="Filtrar por">
      <a href="{{ route('tienda.catalogo', array_diff_key($base, ['segmento' => 1])) }}" class="{{ !$actual ? 'act' : '' }}">Todos</a>
      @foreach($segmentos as $val => $txt)
      <a href="{{ route('tienda.catalogo', $base + ['segmento' => $val]) }}" class="{{ $actual === $val ? 'act' : '' }}" @if($actual === $val) aria-current="true" @endif>{{ $txt }}</a>
      @endforeach
    </nav>
    @else<span></span>@endif

    <form class="orden" method="get" action="{{ route('tienda.catalogo') }}">
      @foreach(array_filter(['categoria' => $filtros['categoria'] ?? null, 'q' => $filtros['q'] ?? null, 'filtro' => $filtros['filtro'] ?? null, 'segmento' => $actual]) as $k => $v)<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endforeach
      <span class="conteo">{{ $productos->count() }} {{ $productos->count() === 1 ? 'modelo' : 'modelos' }}</span>
      <label for="orden" class="sr-only">Ordenar por</label>
      <select id="orden" name="orden" onchange="this.form.submit()">
        @foreach($ordenes as $val => $txt)<option value="{{ $val }}" @selected(($filtros['orden'] ?? '') === $val)>{{ $txt }}</option>@endforeach
      </select>
      <noscript><button class="btn btn-claro btn-chico">Ordenar</button></noscript>
    </form>
  </div>

  @if($productos->isEmpty())
    <div class="vacio">
      <div class="circ">@include('htmlv.partials.icono', ['i' => 'buscar'])</div>
      <h2>No encontramos productos</h2>
      <p>Prueba con otro filtro o revisa todo el catálogo.</p>
      <a class="btn btn-primario" href="{{ route('tienda.catalogo') }}">Ver todo el catálogo</a>
    </div>
  @else
    <div class="grid-prod">
      @foreach($productos as $p)@include('htmlv.partials.tarjeta', ['p' => $p])@endforeach
    </div>
  @endif
</div>
@endsection
