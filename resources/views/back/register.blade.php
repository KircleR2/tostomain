@extends('back.layouts._layout_main')
@php
if (request()->get('ref')) {
    $title = 'Te mando un regalo 🎁 de Tosto Coffee. Abre el link';
} else {
    $title = 'Registro Club Elite - ' . config('app.name');
}
@endphp
@section('title', $title)
@section('description', 'Por tu consumo en Tosto Coffee recibirás puntos que podrás acumular y canjear en todas nuestras sucursales')
@section('content')
  <section>
    <div class="fixed top-0 left-0 w-full flex justify-between opacity-5 -z-10">
      <div><img class="w-full h-screen" src="{{  asset('images/bg-coffee-elements.svg')  }}" alt=""></div>
      <div><img class="w-[500px] mt-16" src="{{  asset('images/right-coffee.svg') }}" alt=""></div>
    </div>
    <div class="fixed bg-[#FFF0E5] top-0 left-0 w-full h-full -z-20"></div>
    <div id="register"></div>
  </section>
@endsection
