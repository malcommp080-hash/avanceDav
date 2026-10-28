@extends('htmlv.layout')

@section('titulo', 'Categorías')

@section('contenido')
@include('htmlv.partials.barra', ['titulo' => 'Categorías', 'migas' => ['Categorías' => null], 'desc' => 'Elige una categoría y filtra por caballero, dama o niños.'])

<div class="contenedor seccion">
  @include('htmlv.partials.avisos')

  <nav class="cats grandes" aria-label="Categorías">
    @foreach($categorias as $c)
    <a class="cat" href="{{ route('tienda.catalogo', ['categoria' => $c->Id_categoria]) }}">
      <span><b>{{ $c->Nombre }}</b><small>{{ $c->productos_count ? $c->productos_count . ' ' . ($c->productos_count === 1 ? 'modelo disponible' : 'modelos disponibles') : 'Próximamente' }}</small></span>
      <span class="circ">@include('htmlv.partials.icono', ['i' => 'flecha'])</span>
    </a>
    @endforeach
    <a class="cat" href="{{ route('tienda.catalogo') }}" style="background:var(--marca-50);border-color:var(--marca-200)">
      <span><b>Ver todo</b><small>Catálogo completo</small></span>
      <span class="circ">@include('htmlv.partials.icono', ['i' => 'flecha'])</span>
    </a>
  </nav>
</div>
@endsection
