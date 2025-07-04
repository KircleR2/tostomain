<div class="container mx-auto py-20 px-6 md:px-0">
  <div class="flex flex-col md:flex-row justify-between p-6 md:p-20 bg-[#523629] rounded-2xl shadow-2xl">
    <div>
      <img class="bg-white w-[300px] p-5 rounded-2xl shadow-2xl" src="{{ asset('images/club-elite-logo.png') }}" alt="">
      <p class="text-sm opacity-60 text-white mt-7">{{ __('*Los puntos no pueden ser utilizados para el pago de promociones') }}</p>
    </div>
    <div class="flex flex-col md:items-end text-left md:text-right">
      <div><h4 class="text-white text-6xl font-action mt-3 md:mt-0">Club Elite</h4></div>
      <p class="font-write text-5xl text-[#F89C24]">{{ __('Registrate y acumula puntos') }}</p>
      <hr class="w-[80px] my-6">
      <p class="text-white text-xl">{!! __('Por tu consumo en Tosto Coffee recibirás <br> puntos que podrás acumular y canjear') !!}</p>
      <a href="{{ route('auth.register') }}" class="text-2xl text-[#009F9A] font-bold flex flex-row justify-center items-center mt-4">{{ __('Registrarme') }}
        <svg class="w-[35px] h-[35px] fill-[#009F9A]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z"/>
        </svg>
      </a>
    </div>
  </div>
</div>
