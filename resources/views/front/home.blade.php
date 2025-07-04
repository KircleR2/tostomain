@extends('front.layouts._layout_main')
@section('title', config('app.name') . ' - ' . __('home_title'))
@section('description', __('home_description'))
@section('content')
  <div id="popup-modal">
    <popup-modal :is-show="true" href="{{ route('front.menu.show', ['menu' => \App\Values\MenuValues::COLD_DRINKS_AND_FRESH['value']]) }}" />
  </div>
  <section id="inicio" class="relative">
    <div class="absolute top-0 left-0 w-full flex justify-between opacity-5 -z-10">
      <div><img class="w-[200px] mt-16" src="{{  asset('images/left-coffee.svg') }}" alt=""></div>
      <div><img class="w-[500px] mt-16" src="{{  asset('images/right-coffee.svg') }}" alt=""></div>
    </div>
    <div class="absolute bg-[#FFF0E5] top-0 left-0 w-full h-full -z-20"></div>
    <div class="container mx-auto flex flex-col md:flex-row items-center justify-between pt-32 md:pt-44 pb-20">
      <div class="flex flex-col justify-center md:justify-start items-center md:items-start px-6 sm:px-0 md:px-0">
        <h1 class="text-7xl text-center md:text-left font-action w-[250px]"><span class="font-write uppercase text-[#009F9A]">{{ __('Café') }}</span> 100% <br>{{ __('Panameño') }}</h1>
        <h2 class="text-2xl w-4/6 my-5 hidden md:block">{{ __('¡Ven a complacer tu paladar con un variado menú de lo que más te gusta!') }}</h2>
        <div class="mt-4 hidden md:block">
          <a class="btn-action text-2xl" href="{{ route('front.menu.index') }}">{{ __('Ver Menú') }}</a>
        </div>
        <div class="hidden md:flex absolute top-[150px] left-96 2xl:left-[650px]">
          <img class="w-[240px] coffee-seeds-image" src="{{ asset('images/coffee-beans.png') }}" alt="">
        </div>
        <div class="mt-10 hidden md:block">
          <div class="font-write uppercase text-[#F89C24] text-3xl">#Momentostosto</div>
        </div>
      </div>
      <div class="flex justify-end relative w-full h-full">
        <div class="absolute top-11 md:-top-10 left-0 sm:left-auto md:left-auto right-0 mx-auto md:right-16 w-[300px]">
          <img class="coffee-cup" src="{{ asset('images/coffee.png') }}" alt="">
        </div>
        <div class="block md:hidden w-full h-64"></div>
        <div>
          <svg class="w-[200px] md:w-[400px] hidden md:flex" viewBox="0 0 956 839" fill="#F89C24" xmlns="http://www.w3.org/2000/svg">
            <path d="M737.607 22.9544L737.358 22.6415C626.521 -21.3752 467.922 -1.4408 314.825 84.4376C81.1207 215.335 -51.4 454.919 18.7941 619.463C89.0057 783.955 335.343 811.084 569.048 679.99C750.502 578.285 870.992 411.162 882.907 264.502L883.028 264.537C910.429 72.6079 739.37 23.3831 737.613 22.9544H737.607ZM494.134 371.815C271.509 404.031 160.713 560.476 153.651 543.792C145.459 524.773 214.285 393.29 450.25 340.248C680.812 288.393 777.688 186.983 826.674 133.604C829.735 130.233 831.921 127.093 833.677 124.017C843.603 140.116 851.442 152.467 850.995 175.113C797.638 235.565 704.16 341.551 494.128 371.815H494.134Z"/>
          </svg>
        </div>
      </div>
    </div>
  </section>
  <section id="nosotros" class="relative mt-44 md:mt-0 px-6 sm:px-0 md:px-0">
    <div class="absolute top-0 left-0 w-full flex justify-between opacity-5 -z-10">
      <div><img class="w-[500px] mt-16" src="{{  asset('images/right-coffee.svg')  }}" alt=""></div>
      <div><img class="w-[200px] mt-56" src="{{  asset('images/left-coffee.svg')  }}" alt=""></div>
    </div>
    <div class="container mx-auto grid grid-cols-1 md:grid-cols-2 items-center justify-between pt-5 md:pt-36 pb-20">
      <div class="hidden md:block">
        <img class="-rotate-6 rounded-2xl" src="{{ asset('images/about-img.png') }}" alt="Comida {{ config('app.name') }}">
      </div>
      <div>
        <div>
          <h2 class="text-6xl font-action text-amber-950">Tosto Coffee</h2>
          <h2 class="text-2xl font-normal mb-5 italic">{{ __('¡Somos Más que un Coffee Shop!') }}</h2>
          <div class="space-y-3 text-xl text-gray-700 text-justify mb-6">
            <p><b>Tosto Coffee</b> {{ __('about_text_1') }}
              {{ __('about_text_2') }}
            </p>
            <p>
              <b>{{ __('about_text_3') }}</b>
              <br>
              {{ __('about_text_4') }}
            </p>
            <p>
              <b>{{ __('about_text_5') }}</b>
              <br>
              {{ __('about_text_6') }}
            </p>
          </div>
          <div>
            <p class="font-write text-5xl text-[#F89C24]">{!!  __('about_text_7') !!} </p>
            <img class="w-[250px] absolute -bottom-24 md:bottom-0 right-0" src="{{ asset('images/coffee-seed.png') }}" alt="">
          </div>
        </div>
      </div>
    </div>
  </section>
  <section id="menu" class="relative">
    <div class="absolute top-0 left-0 w-full flex justify-between opacity-5 -z-10">
      <div><img class="w-[800px] h-full" src="{{  asset('images/bg-coffee-elements.svg')  }}" alt=""></div>
    </div>
    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-transparent to-[#FFF8E5] opacity-60 -z-10"></div>
    <div class="container mx-auto pt-20 px-6 sm:px-0 md:px-0">
      <div class="flex flex-row items-center justify-between mb-8">
        <div class="space-y-3">
          <div class="flex">
            <a href="{{ route('front.menu.index') }}"><h4 class="font-action uppercase text-5xl text-white bg-amber-950 px-5 py-2">{{ __('Menú') }}</h4></a>
          </div>
          <div class="bg-[#009F9A] px-5 py-2">
            <h4 class="font-write text-3xl text-white">Food and Drinks</h4>
          </div>
        </div>
        <div class="hidden md:flex">
          <img class="w-[150px]" src="{{ asset('images/logo.svg') }}" alt="Logo">
        </div>
      </div>
    </div>
    <div id="menu-carrousel" class="relative"></div>
    @include('front.components.club-elite')
  </section>
  <section id="galeria">
    <div class="container mx-auto py-20">
      <div class="flex flex-row items-center justify-between mb-8 px-6 sm:px-0 md:px-0">
        <div class="space-y-3">
          <div class="flex">
            <h4 class="font-action uppercase text-5xl text-white bg-amber-950 px-5 py-2">{{ __('Galería') }}</h4>
          </div>
          <div class="bg-[#009F9A] px-5 py-2">
            <h4 class="font-write text-3xl uppercase text-white">#Momentostosto</h4>
          </div>
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-10 px-6 sm:px-0 md:px-0">
        @for($i = 1; $i < 16; $i++)
          <div>
            <img class="rounded-2xl" src="{{ asset("images/gallery/$i.png") }}" alt="{{ $i }}">
          </div>
        @endfor
      </div>
      <div class="mt-8">
        <a target="_blank" class="flex flex-row justify-center items-center" href="https://www.instagram.com/tostocoffee/">
          <div class="mr-5">
            <svg class="fill-[#009F9A] w-[30px]" viewBox="0 0 24 24"><path d="M7.8,2H16.2C19.4,2 22,4.6 22,7.8V16.2A5.8,5.8 0 0,1 16.2,22H7.8C4.6,22 2,19.4 2,16.2V7.8A5.8,5.8 0 0,1 7.8,2M7.6,4A3.6,3.6 0 0,0 4,7.6V16.4C4,18.39 5.61,20 7.6,20H16.4A3.6,3.6 0 0,0 20,16.4V7.6C20,5.61 18.39,4 16.4,4H7.6M17.25,5.5A1.25,1.25 0 0,1 18.5,6.75A1.25,1.25 0 0,1 17.25,8A1.25,1.25 0 0,1 16,6.75A1.25,1.25 0 0,1 17.25,5.5M12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9Z" /></svg>
          </div>
          <div class="text-2xl uppercase font-write hover:text-[#F89C24]">{{ __('¡Ver más en Instagram!') }}</div>
        </a>
      </div>
    </div>
  </section>
  <section id="sucursales" class="relative py-36 overflow-hidden">
    <div class="absolute top-0 left-0 w-full flex justify-between opacity-5 -z-10">
      <div><img class="w-[900px] mt-16" src="{{  asset('images/bg-burger-elements.svg') }}" alt=""></div>
    </div>
    <div class="absolute bg-[#513628] top-0 left-0 w-full h-full -z-20"></div>
    <div class="container mx-auto">
      <div class="flex flex-col text-center space-y-4">
        <h3 class="font-action text-[#009F9A] text-6xl">{{ __('Sucursales') }}</h3>
        <h4 class="font-write text-white text-7xl uppercase">{!! __('gallery_text_1') !!} </h4>
        <h6 class="uppercase font-bold text-xl text-[#F89C24]">{{ __('gallery_text_2') }}</h6>
      </div>
    </div>
    <div id="branches-carrousel" class="mt-12 relative"></div>
  </section>
@endsection
