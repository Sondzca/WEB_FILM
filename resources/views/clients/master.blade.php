<!DOCTYPE html>
<html lang="en">

<head>
    <title>Shoppers &mdash; Colorlib e-Commerce Template</title>
    <meta charset="utf-8">
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
    <div class="container mt-3">
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


        @yield('content_client')
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
