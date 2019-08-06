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

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <script src="https://unpkg.com/gijgo@1.9.13/js/messages/messages.es-es.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />

    <title>Cesam - @yield('title')</title>
</head>

<body>
    <div class="top-bar">
        <div><img class="" src="{{asset('img/logo.png')}}" alt="" width="185" height="46"></div>
        <!-- El logout no se puede realizar por link, puesto que sería enviarlo por get y este debe
        enviarse por POST, lo que se hace es cuando se presione el link es que no se ejecute (prevenDefault)
        y que con javascript busque el formulario de logout que está invisible y se ejecute con metodo POST-->
        <div class="welcome"><span id="saludo">Hola, {{$auth->nombre}} | </span><a href="{{ route('logout') }}" onclick="event.preventDefault();
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

                    <nav id="sidebar">
                        <li class="nav-item">
                            <a class="nav-link @yield('active-ingresarpersonas')" href="#people_Submenu" data-toggle="collapse" class="dropdown-toggle"><i class="material-icons">person_add</i><span>Igreso de Datos</span></a>
                            <ul class="collapse list-unstyled" id="people_Submenu">
                                <li>
                                <a class="nav-link @yield('active-ingresarpaciente')" href="{{url('registrarpaciente')}}"><span>Ingresar paciente</span></a>
                                </li>
                                <li>
                                <a class="nav-link @yield('active-ingresarfuncionario')" href="{{url('registrarfuncionario')}}"><span>Ingresar funcionario</span></a>
                                </li>
                            </ul>
                        </li>
                    </nav>

                    <nav id="sidebar">
                        <li class="nav-item">
                            <a class="nav-link @yield('active-ingresardatos')" href="#data_Submenu" data-toggle="collapse" class="dropdown-toggle"><i class="material-icons">add_box</i><span>Igreso de Datos</span></a>
                            <ul class="collapse list-unstyled" id="data_Submenu">
                                <li>
                                    <a class="nav-link @yield('active-ingresarespecialidad')" href="{{url('registrarespecialidad')}}"><span>Ingresar Especialidad </span></a>
                                </li>
                                <li>
                                    <a class="nav-link @yield('active-ingresarsexo')" href="{{url('registrarsexo')}}"><span>Ingresar Genero/Sexo </span></a>
                                </li>
                                <li>
                                    <a class="nav-link @yield('active-ingresaratributos')" href="{{url('registraratributos')}}"><span>Ingresar atributos </span></a>
                                </li>
                                <li>
                                    <a class="nav-link @yield('active-ingresaralta')" href="{{url('registraralta')}}"><span>Ingresar tipo de alta</span></a>
                                </li>
                            </ul>
                        </li>
                    </nav>

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
    <script src="{{asset('js/jquery-3.4.0.min.js')}}"></script>
</body>

</html>