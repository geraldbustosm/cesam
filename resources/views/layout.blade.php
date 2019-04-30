<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Cesam - @yield('title')</title>
</head>
<body>
    <div class="main-bar">
        <div><img class="" src="{{asset('img/logo.png')}}" alt="" width="185" height="46"></div>
        <div class="welcome"><span id="saludo">Hola, @yield('user') |</span> <a href="">Salir</a></span></div>
    </div>
    
    <div class="wrap">
        <div class="dashboard">
            <nav>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#"><i class="material-icons">home</i><span>Home</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#"><i class="material-icons">vertical_split</i><span>Escritorio</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="material-icons">people</i><span>Pacientes</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="material-icons">assignment</i><span>Prestaciones</a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="page-content">
            @yield('content')
        </div>
    </div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>