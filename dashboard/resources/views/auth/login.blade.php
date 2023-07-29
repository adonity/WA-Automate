<x-guest-layout>

    <div id="login-button">
        <div class="blur"></div>
        <div class="front-log">
            <img src="{{ asset('/images/logo/logo.png') }}"/>
        </div>
    </div>
    <div id="container">
        <div class="blur2"></div>
        <span class="close-btn">
            <img src="https://cdn4.iconfinder.com/data/icons/miu/22/circle_close_delete_-128.png"/>
        </span>
        @if (session('status'))
        <p class="text-center">{{ session('status') }}</p>
        @endif
        @if ($errors->any())
        <p class="text-center">{{ $errors->first() }}</p>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <input type="email" name="email" placeholder="E-mail" value="{{ old('email') }}">
            <input type="password" name="password" placeholder="Password">
            <button>Login</button>

            <div id="remember-container">
                <input type="checkbox" name="remember" id="checkbox-2-1 flexCheckDefault" class="checkbox" checked="checked"/>
                <span id="remember">Remember me</span>
                <a href="https://api.whatsapp.com/send/?phone=85589878691&text&type=phone_number&app_absent=0">Contact admin</a>
            </div>
        </form>
    </div>

    <div id="forgotten-container">
         <h1>Forgotten</h1>
        <span class="close-btn">
            <img src="https://cdn4.iconfinder.com/data/icons/miu/22/circle_close_delete_-128.png"/>
        </span>

        <form>
            <input type="email" name="email" placeholder="E-mail">
            <a href="#" class="orange-btn">Contact admin</a>
        </form>
    </div>
    {{-- ya --}}

    {{-- <div class="custom-auth">
        <div id="auth-left">
            <div class="auth-logo">
                <a href="index.html">
                    <img src="{{ asset('/images/logo/baltic_brand.png') }}" alt="Logo"></a>
            </div>
            <div class="text-center">
                <h1 class="auth-title">Login</h1>
                <p class="auth-subtitle mb-5">Login with your data that registered by admin</p>
            </div>

            @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
            @endif
            @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
            @endif
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group position-relative has-icon-left mb-4">
                    <input class="form-control form-control-xl" type="email" name="email" placeholder="Email"
                        value="{{ old('email') }}">
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" class="form-control form-control-xl" name="password" placeholder="Password"
                        placeholder="Password">
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                </div>
                <div class="form-check form-check-lg d-flex align-items-end">
                    <input class="form-check-input me-2" type="checkbox" name="remember" id="flexCheckDefault">
                    <label class="form-check-label text-gray-600" for="flexCheckDefault">
                        Keep me logged in
                    </label>
                </div>
                <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Login</button>
            </form>
            <div class="text-center mt-5 text-lg fs-4">
                @if (Route::has('register'))
                <p class="text-gray-600">Don't have an account? <a href="{{route('register')}}" class="font-bold">Sign
                        up</a>.</p>
                @endif


                @if (Route::has('password.request'))
                <p><a class="font-bold" href="https://api.whatsapp.com/send/?phone=85589878691&text&type=phone_number&app_absent=0">Contact Admin</a></p>
                @endif
            </div>
        </div>
    </div> --}}
</x-guest-layout>
