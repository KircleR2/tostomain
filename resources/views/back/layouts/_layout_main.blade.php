<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('back.components.head')
</head>
<body>
    <main id="app">
      @yield('content')
    </main>
    @include('back.components.scripts')
</body>
</html>
