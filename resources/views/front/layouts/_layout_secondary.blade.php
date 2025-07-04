<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('front.components.head')
</head>
<body>
    <main id="app">
      @yield('content')
    </main>
    @include('front.components.scripts')
</body>
</html>
