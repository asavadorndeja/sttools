<!DOCTYPE html>

@include('layouts.header')

<body>
    <div id="app">


        @include('layouts.navbar')

        @yield('content')

    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

      @yield('script')

</body>

@include('layouts.error')

</html>
