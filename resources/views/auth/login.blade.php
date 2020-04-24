<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{asset('img/favicon.png')}}" sizes="48x48" type="image/png">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/login.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <title>Cesam - Centro salud mental</title>
</head>

<body>
    <div class="container login">
        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif
        <img class="img-fluid mb-4" src="{{asset('img/logo.png')}}">
        <form method="POST" action="{{ route('login') }}" id="loginForm" name="onSubmit">
            @csrf
            <div class="form-group">
                <input type="text" class="form-control form-control {{ $errors->has('rut') ? ' is-invalid' : '' }}" value="{{ old('rut') }}" id="rut" name="rut" placeholder="RUT">
            </div>
            <div class="form-group">
                <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password" placeholder="Contraseña">
            </div>
            <div class="form-group">
                <input type="submit" class="form-control btn btn-primary" value="Entrar">
            </div>
            <div class="text-center">
                <a href="/password/reset">¿Olvidaste tu contraseña?</a>
            </div>
        </form>
    </div>

    <script src="{{asset('js/jquery-3.4.0.min.js')}}"></script>
    <script src="{{asset('js/popper.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/rutValidator.js')}}"></script>

    <script>
        const form = document.getElementById('loginForm');
        form.addEventListener('submit', async (e) => {
            // No reaload
            e.preventDefault();
            // Remove dots and dashes
            await quitarFormato($('#rut').val()).then(res => {
                if (res) {
                    document.getElementById('rut').value = res;
                    document.onSubmit.submit();
                }
            });
        });
    </script>
</body>

</html>