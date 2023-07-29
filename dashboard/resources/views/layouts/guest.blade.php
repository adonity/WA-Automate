<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="icon" type="image/x-icon" href="/images/logo/logo.png">
        <title>Welcome to Baltic</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Vendors -->
        <link rel="stylesheet" href="{{ asset('vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
        <link rel="stylesheet" href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.css') }}">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <link rel="stylesheet" href="{{ mix('css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ mix('css/pages/auth.css') }}">


        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body>
        <div id="auth">
            <div class="row h-100">
                <div class="col-lg-12 col-12">
                    {{ $slot }}
                </div>
                {{-- <div class="col-lg-7 d-none d-lg-block">
                    <div id="auth-right">

                    </div>
                </div> --}}
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.16.1/TweenMax.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

         <script>
            $("#login-button").click(function () {
            $("#login-button").fadeOut("slow", function () {
                $("#container").fadeIn();
                TweenMax.from("#container", 0.4, { scale: 0, ease: Sine.easeInOut });
                TweenMax.to("#container", 0.4, { scale: 1, ease: Sine.easeInOut });
            });
            });

            $(".close-btn").click(function () {
            TweenMax.from("#container", 0.4, { scale: 1, ease: Sine.easeInOut });
            TweenMax.to("#container", 0.4, {
                left: "0px",
                scale: 0,
                ease: Sine.easeInOut
            });
            $("#container, #forgotten-container").fadeOut(800, function () {
                $("#login-button").fadeIn(800);
            });
            });

            /* Forgotten Password */
            $("#forgotten").click(function () {
            $("#container").fadeOut(function () {
                $("#forgotten-container").fadeIn();
            });
            });
        </script>
    </body>
</html>
