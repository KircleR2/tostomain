@extends('front.layouts._layout_secondary')
@section('title', 'Nueva Sucursal Altaplaza Mall - ' . config('app.name'))
@section('description', 'Te invitamos a nuestra nueva sucursal en Altaplaza Mall, donde podrás disfrutar de nuestros deliciosos platos y bebidas.')
@section('style')
    <style>
      .imagen-container {
        position: relative;
        width: 100%;
        height: 100vh;
        overflow: hidden;
      }

      .imagen-container img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
      }

      @media screen and (max-width: 768px) {
        .desktop-image {
          display: none;
        }
      }


      @media screen and (min-width: 769px) {
        .mobile-image {
          display: none;
        }
      }
    </style>
@endsection
@section('content')
  <div class="imagen-container">
    <a href="{{ route('auth.register') }}"><img src="{{ asset('images/landing/desktop.png') }}" alt="Imagen para desktop" class="desktop-image"></a>
    <a href="{{ route('auth.register') }}"><img src="{{ asset('images/landing/mobile.png') }}" alt="Imagen para móvil" class="mobile-image"></a>
  </div>
@endsection
