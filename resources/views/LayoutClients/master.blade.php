<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mukta:300,400,700">
    <link rel="stylesheet" href={{ asset('client/fonts/icomoon/style.css') }}>

    <link rel="stylesheet" href={{ asset('client/css/bootstrap.min.css') }}>
    <link rel="stylesheet" href={{ asset('client/css/magnific-popup.css') }}>
    <link rel="stylesheet" href={{ asset('client/css/jquery-ui.css') }}>
    <link rel="stylesheet" href={{ asset('client/css/owl.carousel.min.css') }}>
    <link rel="stylesheet" href={{ asset('client/css/owl.theme.default.min.css') }}>


    <link rel="stylesheet" href={{ asset('client/css/aos.css') }}>

    <link rel="stylesheet" href={{ asset('client/css/style.css') }}>

</head>

<body>
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger text-center">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif



    </div>
    <div class="site-wrap">
        <header class="site-navbar" role="banner">
            <div class="site-navbar-top">
                <div class="container">
                    <div class="row align-items-center">

                        <div class="col-6 col-md-4 order-2 order-md-1 site-search-icon text-left">
                            <form action="" class="site-block-top-search">
                                <span class="icon icon-search2"></span>
                                <input type="text" class="form-control border-0" placeholder="Search">
                            </form>
                        </div>

                        <div class="col-12 mb-3 mb-md-0 col-md-4 order-1 order-md-2 text-center">
                            <div class="site-logo">
                                <a href="{{ route('index') }}" class="js-logo-clone">Tickets</a>
                            </div>
                        </div>

                        <div class="col-6 col-md-4 order-3 order-md-3 text-right">
                            <div class="site-top-icons">
                                <ul>
                                    <li>
                                        @auth
                                            @php
                                                $role = Auth::user()->role;
                                                $redirectRoute = '';

                                                if ($role === 0) {
                                                    $redirectRoute = 'user.dashboard';
                                                } elseif ($role === 1 || $role === 2) {
                                                    $redirectRoute = 'admin.dashboard';
                                                }
                                            @endphp
                                            <span style="color: green">Xin chào:
                                                {{ Auth::user()->fullname ?? Auth::user()->email }}</span>

                                            <a href="{{ route($redirectRoute) }}">
                                                <span class="icon icon-person ms-2"></span>
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}">
                                                <span class="icon icon-person"></span>
                                            </a>
                                        @endauth

                                    </li>
                                    <li>
                                        @php
                                            // Assuming each user has a cart or you can filter by `user_id`
                                            $cartCount = \DB::table('cart_items')->sum('quantity');
                                        @endphp
                                        <a href="{{ route('carts.index') }}" class="site-cart">
                                            <span class="icon icon-shopping_cart"></span>
                                            <span class="count">{{ $cartCount }}</span>
                                        </a>
                                    </li>
                                    <li class="d-inline-block d-md-none ml-md-0"><a href="#"
                                            class="site-menu-toggle js-menu-toggle"><span class="icon-menu"></span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            @include('LayoutClients.menu')
        </header>

        @yield('content_client')


        @include('LayoutClients.footer')
    </div>

    <script src={{ asset('client/js/jquery-3.3.1.min.js') }}></script>
    <script src={{ asset('client/js/jquery-ui.js') }}></script>
    <script src={{ asset('client/js/popper.min.js') }}></script>
    <script src={{ asset('client/js/bootstrap.min.js') }}></script>
    <script src={{ asset('client/js/owl.carousel.min.js') }}></script>
    <script src={{ asset('client/js/jquery.magnific-popup.min.js') }}></script>
    <script src={{ asset('client/js/aos.js') }}></script>

    <script src={{ asset('client/js/main.js') }}></script>

</body>

</html>
