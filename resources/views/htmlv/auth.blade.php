<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('titulo') — Distribuidora David</title>
  <link rel="icon" href="{{ asset('img/logo.jpeg') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/tienda.css') }}?v={{ filemtime(public_path('css/tienda.css')) }}">
</head>
<body class="auth">
  <header class="auth-enc">
    <a href="{{ route('tienda.inicio') }}" class="marca"><img src="{{ asset('img/logo.jpeg') }}" alt=""><span class="nombre">Distribuidora David<small>Ropa · Mayoreo y menudeo</small></span></a>
    <a href="{{ route('tienda.inicio') }}" class="volver">@include('htmlv.partials.icono', ['i' => 'flecha-izq']) Volver a la tienda</a>
  </header>

  <main class="auth-main">
    <div class="auth-caja @yield('ancho')">
      <div class="auth-tarjeta">
        @hasSection('icono')<div class="circ">@include('htmlv.partials.icono', ['i' => trim($__env->yieldContent('icono'))])</div>@endif
        <h1>@yield('titulo')</h1>
        @hasSection('subtitulo')<p class="sub">@yield('subtitulo')</p>@endif

        @if(session('ok'))<div class="alerta ok" role="status">@include('htmlv.partials.icono', ['i' => 'check-circ'])<span>{{ session('ok') }}</span></div>@endif
        @if(session('err'))<div class="alerta err" role="alert">@include('htmlv.partials.icono', ['i' => 'alerta'])<span>{{ session('err') }}</span></div>@endif
        @if($errors->any())<div class="alerta err" role="alert">@include('htmlv.partials.icono', ['i' => 'alerta'])<span>{{ $errors->first() }}</span></div>@endif

        @yield('contenido')
      </div>
      @yield('pie')
    </div>
  </main>

  <script>
    // Mostrar / ocultar contraseña
    document.querySelectorAll('[data-ver-clave]').forEach(b => b.addEventListener('click', () => {
      const i = document.getElementById(b.dataset.verClave), ver = i.type === 'password';
      i.type = ver ? 'text' : 'password';
      b.textContent = ver ? 'Ocultar' : 'Mostrar';
      b.setAttribute('aria-pressed', ver);
    }));
  </script>
</body>
</html>
