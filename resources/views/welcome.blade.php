<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="{{ asset('css/loginapp.css') }}" rel="stylesheet">
	<link href="{{ asset('css/brands.css') }}" rel="stylesheet">
	<link href="{{ asset('css/fontawesome.css') }}" rel="stylesheet">
	<link href="{{ asset('css/solid.css') }}" rel="stylesheet">
	<link href="{{ asset('css/all.min.css') }}" rel="stylesheet">
	<script src="{{ asset('js/all.min.js')}}" defer></script>
	<script src="{{ asset('js/loginapp.js') }}" defer></script>
	<title>Login</title>
</head>

<body>
	<div class="container" id="container">
		<div class="form-container sign-up-container">
			<form action="{{ route('register') }}" method="POST">
				@csrf

				<h1>Create Account</h1>
				<div class="social-container">
					<a href="#" class="social"><i class="fab fa-facebook"></i></a>
					<a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
				</div>
				<span>or use your email for registration</span>

				<input id="name" type="text" placeholder="Name" class="form-control @error('name') is-invalid @enderror"
					name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
				@error('name')
				<span class="invalid-feedback" role="alert">
					<strong>{{ $message }}</strong>
				</span>
				@enderror

				<input id="email" type="email" placeholder="Email"
					class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"
					required autocomplete="email">
				@error('email')
				<span class="invalid-feedback" role="alert">
					<strong>{{ $message }}</strong>
				</span>
				@enderror

				<input id="password" type="password" placeholder="Password"
					class="form-control @error('password') is-invalid @enderror" name="password" required
					autocomplete="new-password">
				@error('password')
				<span class="invalid-feedback" role="alert">
					<strong>{{ $message }}</strong>
				</span>
				@enderror

				<div class="form-group row">
					<label for="password-confirm"
						class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

					<div class="col-md-6">
						<input id="password-confirm" type="password" class="form-control" name="password_confirmation"
							required autocomplete="new-password">
					</div>
				</div>

				<button type="submit">Sign Up</button>
			</form>
		</div>
		<div class="form-container sign-in-container">
			<form action="{{ route('login') }}" class="mt-4" method="post">
				@csrf

				<h1>Sign in</h1>
				<div class="social-container">
					<a href="#" class="social"><i class="fab fa-facebook"></i></a>
					<a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
				</div>
				<span>or use your account</span>
				<input type="email" placeholder="Email" class="form-control @error('email') is-invalid @enderror"
					name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
				@error('email')
				<span class="invalid-feedback" role="alert">
					<strong>{{ $message }}</strong>
				</span>
				@enderror

				<input placeholder="Password" id="password" type="password"
					class="form-control @error('password') is-invalid @enderror" name="password" required
					autocomplete="current-password">
				@error('password')
				<span class="invalid-feedback" role="alert">
					<strong>{{ $message }}</strong>
				</span>
				@enderror

				@if (Route::has('password.request'))
				<a class="btn btn-link pl-0" href="{{ route('password.request') }}">
					Forgot your password?
				</a>
				@endif

				<button type="submit">Sign In</button>
			</form>
		</div>
		<div class="overlay-container">
			<div class="overlay">
				<div class="overlay-panel overlay-left">
					<h1>Welcome Back!</h1>
					<p>To keep connected with us please login with your personal info</p>
					<button class="ghost" id="signIn">Sign In</button>
				</div>
				<div class="overlay-panel overlay-right">
					<h1>Hello, Friend!</h1>
					<p>Enter your personal details and start journey with us</p>
					<button class="ghost" id="signUp">Sign Up</button>
				</div>
			</div>
		</div>
	</div>

</body>

</html>