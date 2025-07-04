@if (!isset($is_section))
  <li><a href="#inicio">{{ __('Inicio') }}</a></li>
  <li><a href="#nosotros">{{ __('Nosotros') }}</a></li>
  <li><a href="{{ route('front.menu.index') }}">{{ __('Menú') }}</a></li>
  <li><a href="#galeria">{{ __('Galería') }}</a></li>
  <li><a href="#sucursales">{{ __('Sucursales') }}</a></li>
@else
  <li><a href="{{ route('front.index') }}">{{ __('Inicio') }}</a></li>
  <li><a href="{{ route('front.index') }}#nosotros">{{ __('Nosotros') }}</a></li>
  <li><a @class(['link-active' => Route::currentRouteName() === 'front.menu.index' ]) href="{{ route('front.menu.index') }}">{{ __('Menú') }}</a></li>
  <li><a href="{{ route('front.index') }}#galeria">{{ __('Galería') }}</a></li>
  <li><a href="{{ route('front.index') }}#sucursales">{{ __('Sucursales') }}</a></li>
@endif
