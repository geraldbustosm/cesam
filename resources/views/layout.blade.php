<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Cesam - @yield('title')</title>
</head>
<body>
    <div class="top-bar">
        <div><img class="" src="{{asset('img/logo.png')}}" alt="" width="185" height="46"></div>
        <div class="welcome"><span id="saludo">Hola, @yield('user') |</span> <a href="">Salir</a></span></div>
    </div>
    
    <div class="wrap">
        <div class="dashboard">
            <nav>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#"><span>Tierras blancas</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @yield('active-escritorio')" href="{{url('dashboard')}}"><i class="material-icons">vertical_split</i><span>Escritorio</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @yield('active-pacientes')" href="{{url('pacientes')}}"><i class="material-icons">people</i><span>Pacientes</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @yield('active-prestaciones')" href="#"><i class="material-icons">assignment</i><span>Prestaciones</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @yield('active-ingresarpaciente')" href="{{url('ingresarpaciente')}}"><i class="material-icons">person_add</i><span id="masdeunalinea">Ingresar paciente</span></a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="page-content">
            @yield('content')
        </div>
    </div>
<script src="{{asset('js/jquery-3.4.0.min.js')}}"</script>
<script src="{{asset('js/popper.min.js')}}"</script>
<script src="{{asset('js/bootstrap.min.js')}"</script>
</body>
</html>