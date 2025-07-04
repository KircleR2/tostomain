<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('front.components.head')
</head>
<body>
    @include('front.components.header')
    <main id="app">
      @yield('content')
    </main>
    @include('front.components.footer')
    @include('front.components.scripts')
</body>
</html>
