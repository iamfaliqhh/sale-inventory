<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/1ab94d0eba.js" crossorigin="anonymous"></script>
    <title>Login | {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <main class="container-login">
        @if(Session::has('account_deactivated'))
            <div class="alert alert-danger" role="alert">
                {{ Session::get('account_deactivated') }}
            </div>
        @endif
        <h2>Login</h2>
        <form id="login" method="post" action="{{ url('/login') }}">
            @csrf
            <div class="input-field">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email') }}"
                    placeholder="Email">
                <div class="underline"></div>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="input-field">
                <input id="password" type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Password" name="password">
                <div class="underline"></div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button id="submit" class="btn btn-primary px-4 d-flex align-items-center"
                type="submit">Login
            </button>
        </form>
    </main>
</body>
</html>