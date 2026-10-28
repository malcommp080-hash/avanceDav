@if(session('ok'))
<div class="alerta ok" role="status">@include('htmlv.partials.icono', ['i' => 'check-circ'])<span>{{ session('ok') }}</span>
  @if(session('agregado'))<a href="{{ route('tienda.carrito') }}">Ver carrito →</a>@endif
</div>
@endif
@if(session('err'))<div class="alerta err" role="alert">@include('htmlv.partials.icono', ['i' => 'alerta'])<span>{{ session('err') }}</span></div>@endif
@if($errors->any())<div class="alerta err" role="alert">@include('htmlv.partials.icono', ['i' => 'alerta'])<span>{{ $errors->first() }}</span></div>@endif
