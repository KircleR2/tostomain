@extends('front.layouts._layout_main')
@section('title', $title . ' | Menú - ' . config('app.name'))
@section('description', config('app.name') . '')
@section('content')
  <div id="lang-modal">
    <lang-modal :is-lang-set="{{ json_encode(request()->cookie('locale')) }}" />
  </div>
  <section class="relative">
    <div class="grid grid-cols-1 md:grid-cols-2">
      <div class="hidden md:block">
        <div class="absolute top-11 left-16">
          <a href="{{ route('front.menu.index') }}" class="flex justify-center items-center text-white font-medium text-2xl space-x-5">
            <div>
              <svg class="w-[50px] drop-shadow-lg fill-white" viewBox="0 0 24 24"><path d="M22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2A10,10 0 0,1 22,12M15.4,16.6L10.8,12L15.4,7.4L14,6L8,12L14,18L15.4,16.6Z" /></svg>
            </div>
            <div class="drop-shadow-lg">{{ __('Regresar') }}</div>
          </a>
        </div>
        <div class="fixed -top-1 -left-1 -z-10">
          <img class="clip-menu-image w-5/12 lg:w-7/12" src="{{ asset('images/menu/' . $tag . '.png') }}" alt="{{ $title }}">
          <svg width="0" height="0">
            <clipPath id="menuPath" clipPathUnits="objectBoundingBox">
              <path d="M0.003,0.001 H1 C1,0.002,1,0.002,1,0.002 C1,0.004,1,0.007,0.999,0.01 C0.995,0.017,0.991,0.027,0.985,0.04 C0.975,0.066,0.96,0.104,0.945,0.149 C0.916,0.24,0.886,0.364,0.883,0.494 C0.881,0.625,0.911,0.752,0.941,0.847 C0.957,0.894,0.972,0.933,0.984,0.96 C0.99,0.974,0.995,0.985,0.998,0.992 C1,0.995,1,0.998,1,1 C1,1,1,1,1,1 H0.003 V0.001"></path>
            </clipPath>
          </svg>
        </div>
      </div>
      <div>
        <div class="absolute top-0 left-0 w-full flex justify-between opacity-5 -z-10">
          <div><img class="w-[200px] mt-16" src="{{  asset('images/left-coffee.svg')  }}" alt=""></div>
          <div><img class="w-[500px] mt-16" src="{{  asset('images/right-coffee.svg')  }}" alt=""></div>
        </div>
        <div class="absolute bg-[#FFF0E5] top-0 left-0 w-full h-full -z-20"></div>
        <div class="flex justify-center items-center mt-6 mb-36 md:my-20">
          <div class="flex flex-col px-6 md:px-0 w-full md:w-8/12">
            <div class="w-full md:w-fit bg-[#513628] px-5 py-2 text-center mb-8">
              @if ($tag === \App\Values\MenuValues::SANDWICHES_BURGERS['value'])
                <h4 class="font-action text-4xl text-white">Sandwiches</h4>
              @else
                <h4 class="font-action text-4xl text-white">{{ __($title) }}</h4>
              @endif
            </div>
            <div class="flex flex-col space-y-7">
              @if ($tag === \App\Values\MenuValues::CHEF_SPECIALTIES['value'])
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Chilli Beans</div>
                    <div class="text-2xl font-bold">$9.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Delicioso chile con carne molida, frijoles y mix de quesos, acompañados con totopos ') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Oven Baked Chicken') }}</div>
                    <div class="text-2xl font-bold">$14.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Muslo encuentro al horno, marinado con aliño especial de la casa, con 2 acompañamientos') }}</div>
                </div>

                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Los 3 Güeys') }}</div>
                    <div class="text-2xl font-bold">$12.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('3 tacos de cerdo desmechado con especies mexicanas, queso mozzarella y pico de gallo') }}</div>
                </div>

                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Pasta de la Nonna') }}</div>
                    <div class="text-2xl font-bold">$14.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Spaghetti con salsa pomodoro y jugosas albóndigas con la receta de la abuela') }}</div>
                </div>

                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('El Padrino') }}</div>
                    <div class="text-2xl font-bold">$12.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Sandwich de pepperoni, salami, queso mozzarella, queso cheddar y mayo chipotle en pan brioche') }}</div>
                </div>
              @endif
              @if ($tag === \App\Values\MenuValues::BREAKFAST_ALL_DAY['value'])
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Clásico') }}</div>
                    <div class="text-2xl font-bold">$10.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('2 huevos al gusto, jamón o tocino o pavo, tostadas, queso crema y mermelada') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('El Panameño') }}</div>
                    <div class="text-2xl font-bold">$12.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Bistec picado, 2 huevos al gusto, 2 tortillas de maíz fritas u hojaldre y queso blanco nacional') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="mb-2"><span class="bg-[#009F9A] text-center text-lg font-write text-white px-3 py-1 rounded-full uppercase">{{ __('¡Nuevo!') }}</span></div>
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Mi caballito encebollado') }}</div>
                    <div class="text-2xl font-bold">$14.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Bistec encebollado y coronado con un huevo frito. Acompañado con hojaldre o tortillas de maíz') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="mb-2"><span class="bg-[#009F9A] text-center text-lg font-write text-white px-3 py-1 rounded-full uppercase">{{ __('¡Nuevo!') }}</span></div>
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Breakfast Burrito') }}</div>
                    <div class="text-2xl font-bold">$10.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Tortilla de harina rellena con huevos revueltos, tomate, cebolla, mix de queso cheddar-mozzarella y mayo chipotle') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Huevos al Gusto') }}</div>
                    <div class="text-2xl font-bold">$3.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('2 huevos fritos o revueltos') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Huevos Criollos') }}</div>
                    <div class="text-2xl font-bold">$10.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('3 huevos fritos con salsa criolla y dos tortillas de maíz fritas u hojaldre') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Salchichas Guisadas') }}</div>
                    <div class="text-2xl font-bold">$8.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Acompañadas con 2 tortillas de maíz fritas u hojaldre') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('La Francesita') }}</div>
                    <div class="text-2xl font-bold">$9.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Nuestra versión de la tostada francesa con pan brioche, mermelada de fresa, dulce de leche, crema chantilly coronada con fresas') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Bowl de Avena') }}</div>
                  </div>
                  <div class="text-2xl text-gray-700 italic">{{ __('Leche regular $6.95 / Leche de almendra $7.95') }}</div>
                  <div class="text-xl text-gray-700">{{ __('Almendras, banana, fresa y chía de blueberries') }}</div>
                  <div class="text-md text-gray-700 italic">{{ __('Extras: cranberry, pasitas, banana, almendras, nueces, $0.50 c/u') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Yogurt Griego con Granola') }}</div>
                    <div class="text-2xl font-bold">$7.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Granola, banana, fresas, y chía de blueberry') }}</div>
                  <div class="text-md text-gray-700 italic">{{ __('Extras: cranberry, pasitas, banana, almendras, nueces, $0.50 c/u') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('El Chiricano') }}</div>
                    <div class="text-2xl font-bold">$13.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Bistec picado, chorizo, almojábanos y queso blanco a la plancha') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Arepa Fit') }}</div>
                    <div class="text-2xl font-bold">$11.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Arepa de linaza, chia y avena, rellena de pollo deshilachado, huevo, crema de aguacate y tomate') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Hojaldre 507') }}</div>
                    <div class="text-2xl font-bold">$13.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Una deliciosa hojaldre coronada con chicharrón, huevo al gusto y queso blanco nacional a la plancha') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Omelette') }}</div>
                    <div class="text-2xl font-bold">$9.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Tomate, pimentón, queso cheddar, cebolla caramelizada, tostadas, queso crema y mermelada.') }}</div>
                  <div class="text-md text-gray-700 italic">{{ __('Extras: Hongos, jamón, pavo, tocino ($2.00 c/u)') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="mb-2"><span class="bg-[#009F9A] text-center text-lg font-write text-white px-3 py-1 rounded-full uppercase">{{ __('¡Nuevo!') }}</span></div>
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Empanada de maíz frita') }}</div>
                    <div class="text-2xl font-bold">$1.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Escoge entre Pollo, Carne o Queso blanco') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="mb-2"><span class="bg-[#009F9A] text-center text-lg font-write text-white px-3 py-1 rounded-full uppercase">{{ __('¡Nuevo!') }}</span></div>
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Empanada de harina horneada') }}</div>
                    <div class="text-2xl font-bold">$2.75</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Pollo, Carne o Queso Blanco') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="mb-2"><span class="bg-[#009F9A] text-center text-lg font-write text-white px-3 py-1 rounded-full uppercase">{{ __('¡Nuevo!') }}</span></div>
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Pastelitos') }}</div>
                    <div class="text-2xl font-bold">$2.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Pollo, Carne o Queso Blanco') }}</div>
                </div>
                <div class="w-full md:w-fit bg-[#513628] px-5 py-2 text-center mb-8">
                  <h4 class="font-action text-4xl text-white">{{ __('Waffles') }}</h4>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Waffles Sencillos') }}</div>
                    <div class="text-2xl font-bold">$5.95</div>
                  </div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Waffles Banana & Nutella') }}</div>
                    <div class="text-2xl font-bold">$8.95</div>
                  </div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Waffles All In') }}</div>
                    <div class="text-2xl font-bold">$11.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Waffle, 2 huevos al gusto, jamón / pavo o tocino, miel o sirope') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Chicken Waffle') }}</div>
                    <div class="text-2xl font-bold">$12.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Crujientes deditos de pollo apanados, sobre un delicioso waffle de la casa acompañado de maple') }}</div>
                  <div class="text-md text-gray-700 italic">Por $1.00 adicional una galleta</div>
                </div>
                <div class="w-full md:w-fit bg-[#513628] px-5 py-2 text-center mb-8">
                  <h4 class="font-action text-4xl text-white">{{ __('Pancakes') }}</h4>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Pancakes (2 Unidades)') }}</div>
                    <div class="text-2xl font-bold">$4.95</div>
                  </div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Pancakes con Huevos al Gusto') }}</div>
                    <div class="text-2xl font-bold">$6.95</div>
                  </div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Pancakes All In') }}</div>
                    <div class="text-2xl font-bold">$11.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('2 Pancakes, 2 huevos al gusto, jamón / pavo o tocino, miel o sirope') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="mb-2"><span class="bg-[#009F9A] text-center text-lg font-write text-white px-3 py-1 rounded-full uppercase">{{ __('¡Nuevo!') }}</span></div>
                  <div class="flex justify-between items-center relative">
                    <div class="font-action text-3xl flex flex-row justify-center items-center">{{ __('Blueberry Pancakes') }}</div>
                    <div class="text-2xl font-bold">$8.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('3 pancakes rellenos de blueberry y coronado con salsa de blueberry') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="mb-2"><span class="bg-[#009F9A] text-center text-lg font-write text-white px-3 py-1 rounded-full uppercase">{{ __('¡Nuevo!') }}</span></div>
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Bananaberry Pancackes') }}</div>
                    <div class="text-2xl font-bold">$8.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Tres esponjosos pancakes, coronados con blueberries, fresas, banana y tierra de galleta de vainilla, bañados con miel de maple') }}</div>
                </div>
                <div class="w-full md:w-fit bg-[#513628] px-5 py-2 text-center mb-8">
                  <h4 class="font-action text-4xl text-white">{{ __('Croissants') }}</h4>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Croissants Relleno') }}</div>
                    <div class="text-2xl font-bold">$6.95</div>
                  </div>
                  <div class="text-md">{{ __('Mozzarella, Cheddar o Blanco Nacional') }}</div>
                  <div class="text-md text-gray-700 italic">{{ __('Extras: Tocino, jamón, pavo $2.00 c/u') }}</div>
                  <div class="text-md text-gray-700 italic">{{ __('Huevo $1.00') }}</div>
                </div>
              @endif
              @if ($tag === \App\Values\MenuValues::TO_SHARE['value'])
                <div class="flex flex-col">
                  <div class="mb-2"><span class="bg-[#009F9A] text-center text-lg font-write text-white px-3 py-1 rounded-full uppercase">{{ __('¡Nuevo!') }}</span></div>
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('El Chino-Panameño') }}</div>
                    <div class="text-2xl font-bold">$12.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Croquetas de arroz con pollo y wantones rellenos de ropa vieja con plátano maduro. Acompañados con salsa sweet chile, alioli de plátano y alioli de culantro') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="mb-2"><span class="bg-[#009F9A] text-center text-lg font-write text-white px-3 py-1 rounded-full uppercase">{{ __('¡Nuevo!') }}</span></div>
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Costillitas de Maíz') }}</div>
                    <div class="text-2xl font-bold">$7.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Deliciosas mazorcas en tiras, con aderezo de parmesano, tajín y mayo chipotle') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="mb-2"><span class="bg-[#009F9A] text-center text-lg font-write text-white px-3 py-1 rounded-full uppercase">{{ __('¡Nuevo!') }}</span></div>
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Los 3 del patio') }}</div>
                    <div class="text-2xl font-bold">$14.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Puerquito frito con salsa asiática dulce. acompañado con mini hojaldres y almojábanos (o tortilla de maíz)') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="mb-2"><span class="bg-[#009F9A] text-center text-lg font-write text-white px-3 py-1 rounded-full uppercase">{{ __('¡Nuevo!') }}</span></div>
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Trío de Empanadas') }}</div>
                    <div class="text-2xl font-bold">$4.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('3 empanaditas de maíz fritas. Carne pollo y queso Acompañadas de chimichurri y alioli') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Alitas Crispy') }}</div>
                  </div>
                  <div class="text-md text-gray-700">{{ __('6 unidades + 1 panecillo + 1 mazorca y blue cheese') }} <b>$8.95</b></div>
                  <div class="text-md text-gray-700">{{ __('12 unidades + 2 panecillos + 2 mazorcas y blue cheese') }} <b>$16.95</b></div>
                  <div class="text-md text-gray-700 italic">* {{ __('Bañadas o con salsa aparte BBQ, Buffalo, Teriyaki o Sweet Spicy') }}</div>
                </div>
              @endif
              @if ($tag === \App\Values\MenuValues::SANDWICHES_BURGERS['value'])
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Pollo Club') }}</div>
                    <div class="text-2xl font-bold">$13.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Filete de pollo, tocino, queso blanco, hongos y cebolla caramelizada') }}</div>
                  <div class="text-md text-gray-700 italic">{{ __('Pan: Ciabatta, Campesino o Brioche') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('SuperClub') }}</div>
                    <div class="text-2xl font-bold">$12.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Nuestra versión del club sandwich, relleno de pollo, jamón, tocino, queso cheddar, queso suizo y salsa de la casa') }}</div>
                  <div class="text-md text-gray-700 italic">{{ __('Pan: Molde Brioche') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Philly A.O.N') }}</div>
                    <div class="text-2xl font-bold">$14.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Tierna carne de res salteada con hongos, cebolla caramelizada, tocino y queso cheddar derretido') }}</div>
                  <div class="text-md text-gray-700 italic">{{ __('Pan: Ciabatta') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Salmón Hipster') }}</div>
                    <div class="text-2xl font-bold">$12.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Salmón ahumado, queso crema, cebolla morada, crema de aguacate y salsa de alcaparras') }}</div>
                  <div class="text-md text-gray-700 italic">{{ __('Pan: Ciabatta, Campesino o Brioche') }}</div>
                </div>
                <hr>
                <div class="w-full md:w-fit bg-[#513628] px-5 py-2 text-center mb-8">
                  <h4 class="font-action text-4xl text-white">Burgers</h4>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Classic Cheeseburguer') }}</div>
                    <div class="text-2xl font-bold">$10.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Tierna carne de res, queso cheddar, pepinillos, cebolla, mayonesa y ketchup') }}</div>
                  <div class="text-md text-gray-700 italic">
                    <span>{{ __('Extra carne: $3.95') }}</span><br>
                    <span>{{ __('Extra tocino: $2.50') }}</span><br>
                    <span>{{ __('Extra queso: $1.5') }}</span><br>
                  </div>
                </div>
                <div class="flex flex-col">
                  <div class="mb-2"><span class="bg-[#009F9A] text-center text-lg font-write text-white px-3 py-1 rounded-full uppercase">{{ __('¡Nuevo!') }}</span></div>
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('BBQ Bacon') }}</div>
                    <div class="text-2xl font-bold">$14.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Jugosa carne de res artesanal, salsa Tosto BBQ , relish de pepinillo y cebolla, tocino y queso cheddar') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="mb-2"><span class="bg-[#009F9A] text-center text-lg font-write text-white px-3 py-1 rounded-full uppercase">{{ __('¡Nuevo!') }}</span></div>
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('La Yeyesónica') }}</div>
                    <div class="text-2xl font-bold">$14.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Jugosa carne de res artesanal, salsa mayo de hongos, queso suizo, cebollas encurtidas y tiras de wantón apanado') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="mb-2"><span class="bg-[#009F9A] text-center text-lg font-write text-white px-3 py-1 rounded-full uppercase">{{ __('¡Nuevo!') }}</span></div>
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('La Cachetona') }}</div>
                    <div class="text-2xl font-bold">$15.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Pan brioche hecho en casa, carne Grass-fed, doble queso provolone, el dúo estrella: chutney de ají trompito con miel de caña y mayonesa ahumada de campo con tiras de bacon crocante.') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('La Burger') }}</div>
                    <div class="text-2xl font-bold">$14.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Tierna carne de res, hongos salteados, tocino, queso cheddar, aderezo de ajo y culantro, lechuga y tomate') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('El Chicken Crispy') }}</div>
                    <div class="text-2xl font-bold">$12.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Crujiente pollo apanado, mayo-pesto, ensalada coleslaw, queso suizo y tocino') }}</div>
                </div>
              @endif
              @if ($tag === \App\Values\MenuValues::LUNCH_DINNER['value'])
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Arroz con pollo') }}</div>
                    <div class="text-2xl font-bold">$12.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Arroz con pollo al estilo panameño, acompañado de ensalada de papas y plátano en tentación') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Sancocho Panameño') }}</div>
                    <div class="text-2xl font-bold">$7.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Acompañado de arroz blanco') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="mb-2"><span class="bg-[#009F9A] text-center text-lg font-write text-white px-3 py-1 rounded-full uppercase">{{ __('¡Nuevo!') }}</span></div>
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Chicken al hongo') }}</div>
                    <div class="text-2xl font-bold">$14.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Filete de pollo a la plancha con una deliciosa salsa de hongo y pimienta. Acompañado con Arroz blanco y ensalada Coleslaw') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Filete de pollo a la parrilla') }}</div>
                    <div class="text-2xl font-bold">$14.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Con relish cítrico de temporada o chimichurri. Acompañado con arroz blanco, lentejas, patacones o coleslaw.') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Tiras de res a la criolla') }}</div>
                    <div class="text-2xl font-bold">$12.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Salteado con vegetales y nuestra salsa criolla. Acompañado con arroz blanco, porotos y ensalada de papas.') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Salmón Oriental') }}</div>
                    <div class="text-2xl font-bold">$18.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Salteado con vegetales y aderezo oriental casero, acompañado con arroz blanco y coleslaw') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Mr. Sweet Chi-Ken') }}</div>
                    <div class="text-2xl font-bold">$14.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Trozos de filete de pollo en salsa agridulce al estilo oriental con vegetales.') }}</div>
                  <div class="text-md text-gray-700 italic">{{ __('*Dos acompañamientos a escoger') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Resputin') }}</div>
                    <div class="text-2xl font-bold">$16.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Carne de res en salsa stroganoff a la pimienta con hongos') }}</div>
                  <div class="text-md text-gray-700 italic">{{ __('*Dos acompañamientos a escoger') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Arroz chombo con puerco') }}</div>
                    <div class="text-2xl font-bold">$14.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Arroz salteado con frijoles negros, puerquito frito, tocino y tropezones de plátano maduro con reducción de salsa spicy dulce.') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Chicken Parmigiana') }}</div>
                    <div class="text-2xl font-bold">$14.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Pollo apanado con salsa pomodoro, queso mozzarella, acompañado con penne salsa blanca') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Penne Alfredo con Pollo') }}</div>
                    <div class="text-2xl font-bold">$12.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Deliciosa pasta con crema y pollo a la parrilla, acompañada con pan de la casa') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="mb-2"><span class="bg-[#009F9A] text-center text-lg font-write text-white px-3 py-1 rounded-full uppercase">{{ __('¡Nuevo!') }}</span></div>
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Penne Strogonoff') }}</div>
                    <div class="text-2xl font-bold">$15.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Pasta penne en crema de hongos y pimienta con trozos de carne de res') }}</div>
                </div>
                <hr>
                <div class="w-full md:w-fit bg-[#513628] px-5 py-2 text-center mb-8">
                  <h4 class="font-action text-4xl text-white">{{ __('Cremas') }}</h4>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Crema de lentejas con carne de res') }}</div>
                    <div class="text-2xl font-bold">$7.95</div>
                  </div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Crema de zapallo') }}</div>
                    <div class="text-2xl font-bold">$7.95</div>
                  </div>
                </div>
                <hr>
                <div class="w-full md:w-fit bg-[#513628] px-5 py-2 text-center mb-8">
                  <h4 class="font-action text-4xl text-white">{{ __('Acompañamientos') }}</h4>
                </div>
                <div class="flex flex-col space-y-3">
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">{{ __('Arroz blanco') }}</div>
                      <div class="text-2xl font-bold">$2.00</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">{{ __('Lentejas') }}</div>
                      <div class="text-2xl font-bold">$1.50</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">{{ __('Porotos') }}</div>
                      <div class="text-2xl font-bold">$1.50</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">{{ __('Papas Fritas') }}</div>
                      <div class="text-2xl font-bold">$3.00</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">{{ __('Papines al Horno') }}</div>
                      <div class="text-2xl font-bold">$3.50</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">{{ __('Camote Frito') }}</div>
                      <div class="text-2xl font-bold">$3.00</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">{{ __('Patacones') }}</div>
                      <div class="text-2xl font-bold">$3.00</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">{{ __('Ensalada Coleslaw') }}</div>
                      <div class="text-2xl font-bold">$1.50</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">{{ __('Ensalada de Papas') }}</div>
                      <div class="text-2xl font-bold">$2.50</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">{{ __('Vegetales salteados') }}</div>
                      <div class="text-2xl font-bold">$2.50</div>
                    </div>
                  </div>
                </div>
              @endif
              @if ($tag === \App\Values\MenuValues::SALAD_BOWLS['value'])
                <div class="w-fit">
                  <h4 class="font-medium font-write uppercase text-3xl text-[#009F9A]">{{ __('Proteína') }}</h4>
                </div>
                <div class="flex flex-col space-y-3">
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">{{ __('Pollo') }}</div>
                      <div class="text-2xl font-bold">$12.95</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">{{ __('Salmón') }}</div>
                      <div class="text-2xl font-bold">$14.95</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">{{ __('Tuna Preparada') }}</div>
                      <div class="text-2xl font-bold">$12.95</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">{{ __('Champiñones') }}</div>
                      <div class="text-2xl font-bold">$12.95</div>
                    </div>
                  </div>
                </div>
                <hr>
                <div class="w-fit">
                  <h4 class="font-medium font-write uppercase text-3xl text-[#009F9A]">Base</h4>
                </div>
                <div class="flex flex-col space-y-3">
                  <div class="font-action text-3xl">{{ __('Mezclum de lechuga') }}</div>
                  <div class="font-action text-3xl">{{ __('Cous cous con vegetales') }}</div>
                  <div class="font-action text-3xl">{{ __('Quinoa') }}</div>
                  <div class="font-action text-3xl">{{ __('Pasta penne') }}</div>
                  <div class="text-xl text-gray-700 italic">{{ __('1 opción (extra $2.50)') }}</div>
                </div>
                <hr>
                <div class="w-fit">
                  <h4 class="font-medium font-write uppercase text-3xl text-[#009F9A]">{{ __('Acompañamientos') }}</h4>
                </div>
                <div class="flex flex-col space-y-3">
                  <div class="font-action text-3xl">{{ __('Remolacha') }}</div>
                  <div class="font-action text-3xl">{{ __('Tomate') }}</div>
                  <div class="font-action text-3xl">{{ __('Aguacate (Temporada)') }}</div>
                  <div class="font-action text-3xl">{{ __('Zanahoria') }}</div>
                  <div class="font-action text-3xl">{{ __('Garbanzos') }}</div>
                  <div class="font-action text-3xl">{{ __('Frijoles') }}</div>
                  <div class="font-action text-3xl">{{ __('Aceitunas Kalamata') }}</div>
                  <div class="font-action text-3xl">{{ __('Rábano') }}</div>
                  <div class="font-action text-3xl">{{ __('Pepino') }}</div>
                  <div class="font-action text-3xl">{{ __('Pimentón') }}</div>
                  <div class="font-action text-3xl">{{ __('Cebolla') }}</div>
                  <div class="font-action text-3xl">{{ __('Queso Feta') }}</div>
                  <div class="text-xl text-gray-700 italic">{{ __('3 opciones (extra $1.25)') }}</div>
                </div>
                <hr>
                <div class="w-fit">
                  <h4 class="font-medium font-write uppercase text-3xl text-[#009F9A]">{{ __('Aderezos') }}</h4>
                </div>
                <div class="flex flex-col space-y-3">
                  <div class="font-action text-3xl">{{ __('Vinagreta balsámica') }}</div>
                  <div class="font-action text-3xl">{{ __('Vinagreta cítrica') }}</div>
                  <div class="font-action text-3xl">{{ __('Vinagreta de frutos rojos') }}</div>
                  <div class="font-action text-3xl">{{ __('Vinagreta clásica') }}</div>
                  <div class="font-action text-3xl">{{ __('Aderezo Caesar') }}</div>
                </div>
              @endif
              @if ($tag === \App\Values\MenuValues::KIDS_MENU['value'])
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Chicken Fingers</div>
                    <div class="text-2xl font-bold">$9.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Crujientes deditos de pollo apanados servidos con honey mustard acompañado de  papas fritas o panecillos') }}</div>
                  <div class="text-md text-gray-700 italic">{{ __('Por $1.00 adicional una galleta') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Cheeseburger</div>
                    <div class="text-2xl font-bold">$9.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Pan de burguer artesanal, ketchup y carne de res con doble queso cheddar acompañado de  papas fritas o camote.') }}</div>
                  <div class="text-md text-gray-700 italic">{{ __('Por $1.00 adicional una galleta') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Chicken fingers Alfredo</div>
                    <div class="text-2xl font-bold">$10.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Pasta al penne cubierta de crema blanca acompañado de trocitos de Chicken fingers o trocitos de pollo a la plancha.') }}</div>
                  <div class="text-md text-gray-700 italic">{{ __('Por $1.00 adicional una galleta') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">{{ __('Chicken Waffle') }}</div>
                    <div class="text-2xl font-bold">$10.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Crujientes deditos de pollo apanados, sobre un delicioso waffle de la casa acompañado de maple') }}</div>
                  <div class="text-md text-gray-700 italic">{{ __('Por $1.00 adicional una galleta') }}</div>
                </div>
              @endif
              @if ($tag === \App\Values\MenuValues::COFFEE_AND_HOT_DRINKS['value'])
                <div class="flex flex-col space-y-3">
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Espresso</div>
                      <div class="text-2xl font-bold">$2.50</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Espresso Macchiato</div>
                      <div class="text-2xl font-bold">$2.95</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Pour Over</div>
                      <div class="text-2xl font-bold">$5.95</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">French Press</div>
                      <div class="text-2xl font-bold">$5.95</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Chocolate Caliente</div>
                      <div class="text-2xl font-bold">$4.95</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Masala Chai</div>
                      <div class="text-2xl font-bold">$4.95</div>
                    </div>
                  </div>
                  <hr>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="flex-1"></div>
                      <div class="flex flex-row space-x-5">
                        <div class="text-md font-bold">Chico</div>
                        <div class="text-md font-bold">Grande</div>
                      </div>
                    </div>
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Cappuccino</div>
                      <div class="flex flex-row space-x-5">
                        <div class="text-2xl font-bold">$3.50</div>
                        <div class="text-2xl font-bold">$4.10</div>
                      </div>
                    </div>
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Cappuccino Tosto</div>
                      <div class="flex flex-row space-x-5">
                        <div class="text-2xl font-bold">$4.50</div>
                        <div class="text-2xl font-bold">$5.50</div>
                      </div>
                    </div>
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Café Americano</div>
                      <div class="flex flex-row space-x-5">
                        <div class="text-2xl font-bold">$3.00</div>
                        <div class="text-2xl font-bold">$3.50</div>
                      </div>
                    </div>
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Café Americano con Leche</div>
                      <div class="flex flex-row space-x-5">
                        <div class="text-2xl font-bold">$3.25</div>
                        <div class="text-2xl font-bold">$3.75</div>
                      </div>
                    </div>
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Café Latte</div>
                      <div class="flex flex-row space-x-5">
                        <div class="text-2xl font-bold">$3.50</div>
                        <div class="text-2xl font-bold">$4.10</div>
                      </div>
                    </div>
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Café Latte CON Sabor</div>
                      <div class="flex flex-row space-x-5">
                        <div class="text-2xl font-bold">$3.75</div>
                        <div class="text-2xl font-bold">$4.50</div>
                      </div>
                    </div>
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Mochaccino</div>
                      <div class="flex flex-row space-x-5">
                        <div class="text-2xl font-bold">$4.50</div>
                        <div class="text-2xl font-bold">$5.50</div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="w-full md:w-fit bg-[#513628] px-5 py-2 text-center mb-8">
                  <h4 class="font-action text-4xl text-white">{{ __('Té e Infusiones') }}</h4>
                </div>
                <div class="flex flex-col space-y-3">
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Cramberry</div>
                      <div class="text-2xl font-bold">$4.50</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Matcha</div>
                      <div class="text-2xl font-bold">$4.50</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Peppermint</div>
                      <div class="text-2xl font-bold">$4.50</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Green Tea Sencha</div>
                      <div class="text-2xl font-bold">$4.50</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Mango Flip</div>
                      <div class="text-2xl font-bold">$4.50</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Rooibois Marzipan</div>
                      <div class="text-2xl font-bold">$4.50</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Lomongrass</div>
                      <div class="text-2xl font-bold">$4.50</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Black Tea Advent</div>
                      <div class="text-2xl font-bold">$4.50</div>
                    </div>
                  </div>
                </div>
              @endif
              @if ($tag === \App\Values\MenuValues::COLD_DRINKS_AND_FRESH['value'])
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Iced Coffee Latte</div>
                    <div class="text-2xl font-bold">$4.50</div>
                  </div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Iced Bubble Coffee Latte</div>
                    <div class="text-2xl font-bold">$5.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Caramelo, café, leche y bolitas de tapioca') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Cold Brew</div>
                    <div class="text-2xl font-bold">$4.95</div>
                  </div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Cold Brew Tosto</div>
                    <div class="text-2xl font-bold">$5.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Leche de Almendras y Avellana') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Blueberry Banana Twist</div>
                    <div class="text-2xl font-bold">$5.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Blueberries, banana, queso crema y miel') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Mystic Chai Caramel</div>
                    <div class="text-2xl font-bold">$5.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Chai mix, canela, café, caramelo y leche') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Choco-Hazel Iced Coffee</div>
                    <div class="text-2xl font-bold">$5.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Crema de cacao y avellanas, café y leche') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Frozen Choco-Coffee Blast</div>
                    <div class="text-2xl font-bold">$5.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Chocolate, Café, leche y más chocolate') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Buda Matcha</div>
                    <div class="text-2xl font-bold">$5.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Matcha mix y leche cremosa') }}</div>
                </div>
                <hr>
                <div class="w-full md:w-fit bg-[#513628] px-5 py-2 text-center mb-8">
                  <h4 class="font-action text-4xl text-white">Fresh Cold Drinks</h4>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Sunkissed Jamaican Bliss</div>
                    <div class="text-2xl font-bold">$3.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Flor de Jamaica, naranja y canela') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Blue Lemonade Club</div>
                    <div class="text-2xl font-bold">$4.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Limón, blue curacao y club soda') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Pink Lemonade</div>
                    <div class="text-2xl font-bold">$4.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Limón y sirope de cereza') }}</div>
                </div>
                <div class="w-full md:w-fit bg-[#513628] px-5 py-2 text-center mb-8">
                  <h4 class="font-action text-4xl text-white">{{ __('Jugos y Licuados') }} $3.75</h4>
                </div>
                <div class="flex flex-col space-y-3">
                  <div class="font-action text-3xl">{{ __('Piña') }}</div>
                  <div class="font-action text-3xl">{{ __('Naranja') }}</div>
                  <div class="font-action text-3xl">{{ __('Maracuya') }}</div>
                  <div class="font-action text-3xl">{{ __('Fresa') }}</div>
                  <div class="font-action text-3xl">{{ __('Papaya') }}</div>
                  <div class="font-action text-3xl">{{ __('Limonada') }}</div>
                  <div class="font-action text-3xl">{{ __('Melón') }}</div>
                </div>
              @endif
              @if ($tag === \App\Values\MenuValues::TOSTO_BAR['value'])
                <div class="w-fit">
                  <h4 class="font-medium font-write uppercase text-3xl text-[#009F9A]">Cocktails</h4>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Carajillo</div>
                    <div class="text-2xl font-bold">$6.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Café + Licor 43 A las rocas/ Agitado/ Con un Twist') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Mojito</div>
                    <div class="text-2xl font-bold">$6.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Ron + Azúcar + Limón + Hierbabuena + Club Soda Clásico / Maracuyá') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Piña Colada</div>
                    <div class="text-2xl font-bold">$6.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Ron + leche de coco + piña') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Blue Tosto</div>
                    <div class="text-2xl font-bold">$8.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Vodka + blue curacao + limón') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Tropical Daiquirí</div>
                    <div class="text-2xl font-bold">$8.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Ron + triple sec + jugo de maracuyá y naranja') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Whisky Sour</div>
                    <div class="text-2xl font-bold">$9.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Whisky + Jarabe de Azúcar + Limón') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Mimosa</div>
                    <div class="text-2xl font-bold">$5.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Espumante + Jugo de Naranja') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Margarita</div>
                    <div class="text-2xl font-bold">$7.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Tequila + Triple Sec + Limón Maracuyá / Fresa A las rocas / Frozzen') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Aperol Spritz</div>
                    <div class="text-2xl font-bold">$8.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Aperol + Espumante + Club Soda') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Gin Tónic</div>
                    <div class="text-2xl font-bold">$8.95</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Ginebra + Tónica / Clásico con Pepino / Frutos Rojos') }}</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Red Bull Tropical Gin</div>
                    <div class="text-2xl font-bold">$7.50</div>
                  </div>
                  <div class="text-xl text-gray-700">Red Bull + Gin</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Sangría Blanca</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Copa') }} $5.25 / {{ __('Jarra') }} $25</div>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Sangría</div>
                  </div>
                  <div class="text-xl text-gray-700">{{ __('Copa') }} $5.25 / {{ __('Jarra') }} $25</div>
                </div>
                <div class="w-fit">
                  <h4 class="font-medium font-write uppercase text-3xl text-[#009F9A]">{{ __('Sodas') }}</h4>
                </div>
                <div class="flex flex-col space-y-3">
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Coca Cola</div>
                      <div class="text-md text-gray-700 italic">{{ __('Regular | Zero | Light') }}</div>
                      <div class="text-2xl font-bold">$2.50</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Ginger Ale</div>
                      <div class="text-2xl font-bold">$2.50</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Fresa</div>
                      <div class="text-2xl font-bold">$2.50</div>
                    </div>
                  </div>
                </div>
                <div class="w-fit">
                  <h4 class="font-medium font-write uppercase text-3xl text-[#009F9A]">{{ __('Vinos') }}</h4>
                </div>
                <div class="flex flex-col">
                  <div class="flex justify-between items-center">
                    <div class="flex-1"></div>
                    <div class="flex flex-row space-x-5">
                      <div class="text-md font-bold">{{ __('Copa') }}</div>
                      <div class="text-md font-bold">{{ __('Botella') }}</div>
                    </div>
                  </div>
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Amigo Perro Merlot</div>
                    <div class="flex flex-row space-x-5">
                      <div class="text-2xl font-bold">$5.95</div>
                      <div class="text-2xl font-bold">$27.50</div>
                    </div>
                  </div>
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Momentos Malbec</div>
                    <div class="flex flex-row space-x-5">
                      <div class="text-2xl font-bold">$6.95</div>
                      <div class="text-2xl font-bold">$29.50</div>
                    </div>
                  </div>
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Momentos Pinot Noir</div>
                    <div class="flex flex-row space-x-5">
                      <div class="text-2xl font-bold">$5.95</div>
                      <div class="text-2xl font-bold">$27.50</div>
                    </div>
                  </div>
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Amigo Perro Sauvignon Blanc</div>
                    <div class="flex flex-row space-x-5">
                      <div class="text-2xl font-bold">$6.95</div>
                      <div class="text-2xl font-bold">$29.50</div>
                    </div>
                  </div>
                  <div class="flex justify-between items-center">
                    <div class="font-action text-3xl">Amigo Perro Chardonnay</div>
                    <div class="flex flex-row space-x-5">
                      <div class="text-2xl font-bold">$6.95</div>
                      <div class="text-2xl font-bold">$29.50</div>
                    </div>
                  </div>
                </div>
                <div class="w-fit">
                  <h4 class="font-medium font-write uppercase text-3xl text-[#009F9A]">{{ __('Tragos') }}</h4>
                </div>
                <div class="flex flex-col space-y-3">
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Ron</div>
                      <div class="text-2xl font-bold">$6.95</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Whisky</div>
                      <div class="text-2xl font-bold">$8.00</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Tequila</div>
                      <div class="text-2xl font-bold">$6.95</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Vodka</div>
                      <div class="text-2xl font-bold">$7.95</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Gin</div>
                      <div class="text-2xl font-bold">$7.95</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">LIcor 43</div>
                      <div class="text-2xl font-bold">$5.95</div>
                    </div>
                  </div>
                </div>
                <div class="text-2xl font-action uppercase text-center">{!! __('Sólo incluimos zumos como mixers <br>(sodas y otros mixers se piden aparte)') !!}</div>
                <div class="w-fit">
                  <h4 class="font-medium font-write uppercase text-3xl text-[#F39011]">{{ __('Cervezas Nacionales') }}</h4>
                </div>
                <div class="flex flex-col space-y-3">
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Panamá Regular</div>
                      <div class="text-2xl font-bold">$2.50</div>
                    </div>
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Panamá Light</div>
                      <div class="text-2xl font-bold">$2.50</div>
                    </div>
                  </div>
                </div>
                <div class="w-fit">
                  <h4 class="font-medium font-write uppercase text-3xl text-[#F39011]">{{ __('Cervezas Importadas') }}</h4>
                </div>
                <div class="flex flex-col space-y-3">
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Heineken</div>
                      <div class="text-2xl font-bold">$3.50</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Heineken Silver</div>
                      <div class="text-2xl font-bold">$3.50</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Heineken 0.0 ({{ __('Sin Alcohol') }})</div>
                      <div class="text-2xl font-bold">$3.50</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Corona</div>
                      <div class="text-2xl font-bold">$3.50</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Modelo</div>
                      <div class="text-2xl font-bold">$3.50</div>
                    </div>
                  </div>
                </div>
                <div class="w-fit">
                  <h4 class="font-medium font-write uppercase text-3xl text-[#F39011]">{{ __('Cervezas Artesanales') }}</h4>
                </div>
                <div class="flex flex-col space-y-3">
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">India Dormida</div>
                      <div class="text-2xl font-bold">$5.50</div>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">River Down</div>
                      <div class="text-2xl font-bold">$5.50</div>
                    </div>
                  </div>
                </div>
                <div class="w-fit">
                  <h4 class="font-medium font-write uppercase text-3xl text-[#009F9A]">{{ __('Otras Bebidas') }}</h4>
                </div>
                <div class="flex flex-col space-y-3">
                  <div class="flex flex-col">
                    <div class="flex justify-between items-center">
                      <div class="font-action text-3xl">Kombucha</div>
                      <div class="text-2xl font-bold">$4.50</div>
                    </div>
                  </div>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
