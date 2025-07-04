@extends('front.layouts._layout_main')
@section('title', 'Menú - ' . config('app.name'))
@section('description', 'Explora nuestro delicioso menú de almuerzos y bebidas en ' . config('app.name') . ' en Panamá. Desde cafés artesanales hasta jugos frescos y opciones vegetarianas, tenemos algo para todos los gustos. ¡Visítanos hoy y descubre por qué somos el coffee shop preferido de la ciudad!')
@section('content')
  <div id="lang-modal">
    <lang-modal :is-lang-set="{{ json_encode(request()->cookie('locale')) }}" />
  </div>
  <section class="relative">
    <div class="absolute top-0 left-0 w-full flex justify-between opacity-5 -z-10">
      <div><img class="w-[800px] h-full" src="{{  asset('images/bg-coffee-elements.svg')  }}" alt=""></div>
    </div>
    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-transparent to-[#FFF8E5] opacity-60 -z-10"></div>
    <div class="container mx-auto pt-10 px-6 sm:px-0 md:px-0">
      <div class="flex flex-row items-center justify-between mb-8">
        <div class="space-y-3">
          <div class="flex">
            <h4 class="font-action uppercase text-5xl text-white bg-amber-950 px-5 py-2">{{ __('Menú') }}</h4>
          </div>
          <div class="bg-[#009F9A] px-5 py-2">
            <h4 class="font-write text-3xl text-white">Food and Drinks</h4>
          </div>
        </div>
      </div>
      <div id="menu-grid" class="relative">
        @php
          $menuData =  json_decode(File::get(resource_path() . '/js/menuData.json'), true);
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        @foreach($menuData as $menuItem)
          <a href="{{ $menuItem['href'] }}">
            <div class="flex flex-col-reverse md:flex-row h-full md:justify-between items-center bg-[#009F9A] rounded-2xl py-10 px-6 md:px-12 border-[#009388]">
              <div class="w-[220px]">
                <div class="text-black font-action text-3xl">{{ __($menuItem['name']) }}</div>
                <div class="text-gray-800 font-medium text-xl">{{ __($menuItem['description']) }}</div>
              </div>
              <div class="mb-6 md:mb-0">
                <img class="w-[210px] rounded-full" src="{{ asset($menuItem['img']) }}" alt="{{ $menuItem['name'] }}">
              </div>
            </div>
          </a>
        @endforeach
        </div>
      </div>
    </div>
    @include('front.components.club-elite')
  </section>
@endsection
