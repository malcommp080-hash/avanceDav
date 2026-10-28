<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('titulo', 'Tienda') — Distribuidora David</title>
  <meta name="description" content="Ropa para caballero, dama y niños con precio de mayoreo. Distribuidora David, San Martín Texmelucan, Puebla.">
  <link rel="icon" href="{{ asset('img/logo.jpeg') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/tienda.css') }}?v={{ filemtime(public_path('css/tienda.css')) }}">
  @stack('head')
</head>
<body>
@php
  $piezasCarrito = \App\Services\Carrito::piezas();
  $navActual = fn ($cond) => $cond ? 'act' : '';
@endphp

<a href="#contenido" class="sr-only">Saltar al contenido</a>

<div class="franja">Precio de <b>mayoreo</b> desde 5 piezas por modelo · Combina tallas · Paga por depósito o transferencia</div>

<header class="enc" id="enc">
  <div class="enc-fila">
    <a href="{{ route('tienda.inicio') }}" class="marca">
      <img src="{{ asset('img/logo.jpeg') }}" alt="">
      <span class="nombre">Distribuidora David<small>Ropa · Mayoreo y menudeo</small></span>
    </a>

    <form class="buscador" action="{{ route('tienda.catalogo') }}" method="get" role="search">
      @include('htmlv.partials.icono', ['i' => 'buscar'])
      <label for="q" class="sr-only">Buscar productos</label>
      <input type="search" id="q" name="q" placeholder="Busca chamarras, polos, jeans…" value="{{ request('q') }}" maxlength="60" autocomplete="off">
      <button type="submit">Buscar</button>
    </form>

    <div class="enc-acciones">
      @if(session('cliente_id'))
        <a href="{{ route('tienda.pedidos') }}" class="accion" title="Mis pedidos">@include('htmlv.partials.icono', ['i' => 'usuario'])<span class="txt">{{ \Illuminate\Support\Str::of(session('cliente_nombre'))->before(' ') }}</span></a>
        <form action="{{ route('tienda.logout') }}" method="post">@csrf
          <button type="submit" class="accion" title="Cerrar sesión">@include('htmlv.partials.icono', ['i' => 'salir'])<span class="sr-only">Cerrar sesión</span></button>
        </form>
      @else
        <a href="{{ route('tienda.login') }}" class="accion">@include('htmlv.partials.icono', ['i' => 'usuario'])<span class="txt">Entrar</span></a>
      @endif
      <a href="{{ route('tienda.carrito') }}" class="accion" aria-label="Carrito, {{ $piezasCarrito }} piezas">
        @include('htmlv.partials.icono', ['i' => 'bolsa'])<span class="txt">Carrito</span>
        @if($piezasCarrito)<span class="globo">{{ $piezasCarrito > 99 ? '99+' : $piezasCarrito }}</span>@endif
      </a>
    </div>
  </div>

  <nav class="enc-nav" aria-label="Principal">
    <ul>
      <li><a href="{{ route('tienda.inicio') }}" class="{{ $navActual(request()->routeIs('tienda.inicio')) }}">Inicio</a></li>
      @foreach($navCategorias ?? [] as $c)
      <li><a href="{{ route('tienda.catalogo', ['categoria' => $c->Id_categoria]) }}" class="{{ $navActual(request()->routeIs('tienda.catalogo') && (int) request('categoria') === $c->Id_categoria) }}">{{ $c->Nombre }}</a></li>
      @endforeach
      <li><a href="{{ route('tienda.catalogo', ['filtro' => 'ofertas']) }}" class="{{ $navActual(request('filtro') === 'ofertas') }}">Ofertas</a></li>
      <li><a href="{{ route('tienda.catalogo', ['filtro' => 'vendidos']) }}" class="{{ $navActual(request('filtro') === 'vendidos') }}">Más vendidos</a></li>
      <li><a href="{{ route('tienda.categorias') }}" class="{{ $navActual(request()->routeIs('tienda.categorias')) }}">Todas las categorías</a></li>
    </ul>
  </nav>
</header>

<main id="contenido">
  @yield('contenido')
</main>

<footer class="pie">
  <div class="pie-fila">
    <div>
      <a href="{{ route('tienda.inicio') }}" class="marca"><img src="{{ asset('img/logo.jpeg') }}" alt=""><span class="nombre">Distribuidora David<small>Ropa · Mayoreo y menudeo</small></span></a>
      <p>Ropa para toda la familia con precio de mayoreo desde 5 piezas por modelo.</p>
    </div>
    <div>
      <h4>Comprar</h4>
      <ul>
        @foreach(($navCategorias ?? collect())->take(5) as $c)<li><a href="{{ route('tienda.catalogo', ['categoria' => $c->Id_categoria]) }}">{{ $c->Nombre }}</a></li>@endforeach
        <li><a href="{{ route('tienda.catalogo', ['filtro' => 'ofertas']) }}">Ofertas</a></li>
      </ul>
    </div>
    <div>
      <h4>Mi cuenta</h4>
      <ul>
        @if(session('cliente_id'))
          <li><a href="{{ route('tienda.pedidos') }}">Mis pedidos</a></li>
        @else
          <li><a href="{{ route('tienda.login') }}">Iniciar sesión</a></li>
          <li><a href="{{ route('tienda.registro') }}">Crear cuenta</a></li>
        @endif
        <li><a href="{{ route('tienda.carrito') }}">Carrito</a></li>
      </ul>
    </div>
    <div>
      <h4>Visítanos</h4>
      <p style="margin:0">{{ config('dav.direccion') }}</p>
    </div>
  </div>
  <div class="pie-legal"><div><span>© {{ date('Y') }} Distribuidora David</span><span>Pagos por depósito o transferencia · Validados por nuestro equipo</span></div></div>
</footer>

@if(session('cliente_id'))@include('partials.inactividad')@endif
<script>
  // Sombra del encabezado al desplazarse
  (function () {
    const enc = document.getElementById('enc');
    const f = () => enc.classList.toggle('con-sombra', window.scrollY > 8);
    f(); window.addEventListener('scroll', f, { passive: true });
  })();
</script>
@stack('scripts')
</body>
</html>
