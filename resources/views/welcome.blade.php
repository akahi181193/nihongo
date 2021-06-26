<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/loginapp.css') }}" rel="stylesheet">
    <link href="{{ asset('css/about.css') }}" rel="stylesheet">
    <link href="{{ asset('css/brands.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('css/solid.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/all.min.js')}}" defer></script>
    <script src="{{ asset('js/loginapp.js') }}" defer></script>
    <title>NihongoMemo</title>
</head>

{{-- Sign Up Form --}}

<body>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="{{ route('register') }}" method="POST">
                @csrf


                <h1>{{__('Create Account')}}</h1>
                <span>{{__('or use your email for registration')}}</span>
                <input id="name" type="text" placeholder="{{__('Name')}}" class="form-control @error('name') is-invalid @enderror"
                    name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <input id="email" type="email" placeholder="{{_('Email')}}"
                    class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"
                    required autocomplete="email">
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <input id="password" type="password" placeholder="{{__('Password')}}"
                    class="form-control @error('password') is-invalid @enderror" name="password" required
                    autocomplete="new-password">
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror


                <input id="password-confirm" type="password" placeholder="{{_('Confirm Password')}}" class="form-control"
                    name="password_confirmation" required autocomplete="new-password">


                <button type="submit">{{__('Sign Up')}}</button>
            </form>
        </div>

        {{-- Sign In Form --}}

        <div class="form-container sign-in-container">
            <form action="{{ route('login') }}" class="mt-4" method="post">
                @csrf

                <h1>{{__('Sign In')}}</h1>


                <input type="email" placeholder="{{__('Email')}}" class="form-control @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <input placeholder="{{__('Password')}}" id="password" type="password"
                    class="form-control @error('password') is-invalid @enderror" name="password" required
                    autocomplete="current-password">
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="social-container">
                    <a href="{{ url('auth/facebook') }}" class="social"><i class="fab fa-facebook"></i></a>
                    <a href="{{ url('auth/google') }}" class="social"><i class="fab fa-google"></i></a>
                    <a href="{{ url('auth/github') }}" class="social"><i class="fab fa-github"></i></a>
                </div>

                @if (Route::has('password.request'))
                <a class="btn btn-link pl-0" href="{{ route('password.request') }}">
                    {{__('Forgot your password?')}}
                </a>
                @endif


                <button type="submit">{{__('Sign In')}}</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>NihongoMemo</h1>
                    <p>Start to keep your Japanese's memos. Simply just sign up a new account in seconds</p>
                    <button class="ghost" id="signIn">{{__('Sign In')}}</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>NihongoMemo</h1>
                    <p>The simplest way to keep your Japanese's memos in everywhere</p>
                    <button class="ghost" id="signUp">{{__('Sign Up')}}</button>
                </div>
            </div>
        </div>
    </div>

</body>

<div class="footer">
    <p>Copyright &copy; NAL 2021</p>
</div>
<!-- Language -->
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{ Config::get('languages')[App::getLocale()] }}
    </a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
    @foreach (Config::get('languages') as $lang => $language)
        @if ($lang != App::getLocale())
                <a class="dropdown-item" href="{{ route('lang.switch', $lang) }}"> {{$language}}</a>
        @endif
    @endforeach
    </div>
</li>
</html>
