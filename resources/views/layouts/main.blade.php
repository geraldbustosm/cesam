<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{asset('img/favicon.png')}}" sizes="48x48" type="image/png">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Cesam - @yield('title')</title>
</head>
<body>
    <div class="top-bar">
        <div><img class="" src="{{asset('img/logo.png')}}" alt="" width="185" height="46"></div>
        <!-- El logout no se puede realizar por link, puesto que sería enviarlo por get y este debe
        enviarse por POST, lo que se hace es cuando se presione el link es que no se ejecute (prevenDefault)
        y que con javascript busque el formulario de logout que está invisible y se ejecute con metodo POST-->
        <div class="welcome"><span id="saludo">Hola, {{$auth->nombre}} | </span><a 
            href="{{ route('logout') }}" onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">Salir</a></span>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
        </form>
        </div>
    </div>
    <div class="wrap">
        <div class="dashboard">
            <nav>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#"><span>Tierras blancas</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @yield('active-escritorio')" href="{{url('/')}}"><i class="material-icons">vertical_split</i><span>Escritorio</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @yield('active-pacientes')" href="{{url('pacientes')}}"><i class="material-icons">people</i><span>Pacientes</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @yield('active-prestaciones')" href="{{url('fichas')}}"><i class="material-icons">assignment</i><span>Prestaciones</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @yield('active-ingresarpaciente')" href="{{url('registrarpaciente')}}"><i class="material-icons">person_add</i><span>Ingresar paciente</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @yield('active-testing')" href="{{url('testing')}}"><i class="material-icons">bug_report</i><span>Sitio de Pruebas</span></a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="page-content">@yield('content')</div>
    </div>
<script src="{{asset('js/popper.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
</body>
</html>