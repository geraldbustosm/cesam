<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/login.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <title>Cesam - Centro salud mental</title>
</head>
<body>
    <div class="container login">
        <div class="row">
            <div class="col">
                <img class="img-fluid mb-4" src="{{asset('img/logo.png')}}">
                <form action="">
                    <div class="form-group">
                        <input type="text" class="form-control" id="rut" placeholder="RUT">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="pass" placeholder="Contraseña">
                    </div>
                    <div class="form-group">
                        <input type="button" class="form-control btn btn-primary" value="Entrar">
                    </div>
                </form>
            </div>
        </div>
    </div>

<script src="{{asset('js/jquery-3.4.0.min.js')}}"></script>
<script src="{{asset('js/popper.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
</body>
</html>

