<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EMP')</title>

    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/modals.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @vite(['resources/css/SH_style.css', 'resources/js/app.js'])
</head>

<body>

<header class="d-flex flex-wrap justify-content-center py-3 border-bottom">
    <a class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none" href="{{ url('/') }}">
        <span class="fs-2">EMP</span>
        <img alt="EMP Logo" height="30" src="{{ asset('images/device-ssd-fill.svg') }}" width="40">
    </a>
 
    <ul class="nav nav-pills">
    
    <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Domov</a></li>
    <li class="nav-item"><a class="nav-link {{ request()->is('services') ? 'active' : '' }}" href="{{ url('services') }}">Služby</a></li>
    <li class="nav-item"><a class="nav-link {{ request()->is('projects_portfolio') ? 'active' : '' }}" href="{{ url('projects_portfolio') }}">Projekty</a></li>
    <li class="nav-item"><a class="nav-link newpage-link {{ request()->is('orders') ? 'active' : '' }}" href="{{ url('orders') }}">Objednávka</a></li>
    <li class="nav-item"><a class="nav-link {{ request()->is('kontakt') ? 'active' : '' }}" href="{{ url('kontakt') }}">Kontakt</a></li>
    
    @guest
        <li class="nav-item"><a class="nav-link {{ request()->is('register') ? 'active' : '' }}" href="{{ route('register') }}">Sign Up</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->is('login') ? 'active' : '' }}" href="{{ route('login') }}">Login</a></li>
    @endguest

    @auth
       
        <li class="nav-item"><a class="nav-link newpage-link {{ request()->is('smarthome_index') ? 'active' : '' }}" href="{{ route('smart_home.index') }}">SMART_HOME</a></li>

        @if(auth()->check() && auth()->user()->role === 'admin')
        <li class="nav-item"><a class="nav-link newpage-link {{ request()->is('tester/index') ? 'active' : '' }}" href="{{ url('tester/tester_index') }}">TESTER</a></li>
        @endif
   

        <li class="nav-item"><a class="nav-link" style="color: green" >Welcome {{ Auth::user()->name }}</a></li>
        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link" style="color: orange; text-decoration: none;">Logout</button>
            </form>
        </li>
    @endauth

    <script src="{{ asset('js/open_page_on_new_tab.js') }}"></script>
</ul>

<main>
    @yield('content')  <!-- This is where page-specific content will go -->
</main>



</body>

</footer>

</body>
</html>
