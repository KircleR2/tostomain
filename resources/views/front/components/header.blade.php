<nav  @class([(isset($is_section) && $is_section) ? 'nav-secondary' : 'nav menu'])>
  <div class="container mx-auto flex items-center justify-between py-7 px-6 sm:px-0 md:px-0">
    <div class="flex items-center">
      <a href="{{ route('front.index') }}">
        <img src="{{  asset('images/logo.svg')  }}" alt="Logo" class="h-[55px] sm:h-[60px] md:h-16">
      </a>
    </div>
    <div class="flex md:hidden items-center justify-center space-x-6">
      <div id="lang-mobile-modal">
        <lang-modal current-lang="{{ request()->cookie('locale') ?? 'es' }}" ref="langModal" />
      </div>
      <div>
        <a href="{{ route('auth.login') }}">
          <svg class="w-[30px]" viewBox="0 0 27 24" fill="none">
            <path d="M3 18L0 1.5L8.25 9L13.5 0L18.75 9L27 1.5L24 18H3ZM24 22.5C24 23.4 23.4 24 22.5 24H4.5C3.6 24 3 23.4 3 22.5V21H24V22.5Z" fill="#F89C24"/>
          </svg>
        </a>
      </div>
      <div>
        <svg id="menu-icon" class="w-[30px]" viewBox="0 0 30 20" fill="none">
          <path d="M0 0H30V3.33333H0V0ZM0 8.33333H17.5V11.6667H0V8.33333ZM0 16.6667H30V20H0V16.6667Z" fill="#513628"/>
        </svg>
      </div>
    </div>
    <div id="full-menu" class="hidden md:flex items-center text-2xl md:space-x-14">
      <div class="flex md:hidden justify-between absolute top-20 left-0 w-full opacity-5 -z-10">
        <div><img class="w-[200px] mt-16" src="{{  asset('images/left-coffee.svg') }}" alt=""></div>
        <div><img class="w-[500px] mt-16" src="{{  asset('images/right-coffee.svg') }}" alt=""></div>
      </div>
      <ul class="main-menu flex flex-col md:flex-row items-center space-y-11 md:space-y-0 md:space-x-10 font-action">
        @include('front.components.links')
        <li><a href="{{ route('auth.login') }}" class="btn-action px-12">Club Elite</a></li>
        <li>
          <div id="lang-modal">
            <lang-modal current-lang="{{ request()->cookie('locale') ?? 'es' }}" ref="langModal" />
          </div>
        </li>
      </ul>
      <button id="close-icon" class="flex md:hidden absolute top-8 right-6">
        <svg class="w-[40px] fill-[#F89C24]" viewBox="0 0 24 24"><path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" /></svg>
      </button>
    </div>
  </div>
  <ul class="hidden bg-gray-900 text-white py-3 px-5 space-y-5">
    @include('front.components.links')
  </ul>
</nav>
