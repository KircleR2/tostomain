@extends('back.layouts._layout_main')
@section('title', 'Recuperar Contraseña - ' . config('app.name'))
@section('description', 'Recupera tu contraseña para acceder a nuestro de Loyalty')
@section('content')
  <section>
    <div class="fixed top-0 left-0 w-full flex justify-between opacity-20 -z-10">
      <div><img class="w-full h-screen" src="{{  asset('images/bg-coffee-elements.svg')  }}" alt=""></div>
      <div><img class="w-[500px] mt-16" src="{{  asset('images/right-coffee.svg') }}" alt=""></div>
    </div>
    <div class="fixed bg-[#009F9A] top-0 left-0 w-full h-full -z-20"></div>
    <div id="recovery-password"></div>
  </section>
@endsection
