<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0'/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{asset('img/favicon.png')}}" sizes="48x48" type="image/png">
    <!-- CSS -->
    <link rel="stylesheet" href="{{asset('css/sweetalert2.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/material-icons.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/gijgo.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/pace.css')}}">
    <!-- JS -->
    <script src="{{asset('js/jquery-3.4.1.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/select2.min.js')}}"></script>
    <script src="{{asset('js/popper.min.js')}}"></script>
    <script src="{{asset('js/gijgo.min.js')}}"></script>
    <script src="{{asset('js/pace.js')}}"></script>
    <script src="{{asset('js/messages.es-es.min.js')}}"></script>
    <script src="{{asset('js/sweetalert2.min.js')}}"></script>
    <!-- Progress bar -->
    <script>
        paceOptions = {
            elements: true
        };
    </script>

    <title>Cesam - @yield('title')</title>
</head>

<body>
    <div class="hamburger closed">
    <div class="line one"></div>
    <div class="line two"></div>
    <div class="line three"></div>
    </div>
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
        <div class="dashboard menu">
            <nav>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#"><span>Tierras blancas</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @yield('active-escritorio')" href="{{url('/')}}"><i class="material-icons">vertical_split</i><span>Escritorio</span></a>
                    </li>

                    <nav id="sidebar">
                        <li class="nav-item">
                            <a class="nav-link @yield('active-prestaciones')" href="#records_Submenu" data-toggle="collapse" class="dropdown-toggle"><i class="material-icons">assignment</i><span>Prestaciones</span></a>
                            <ul class="collapse list-unstyled" id="records_Submenu">
                                <li class="nav-item">
                                    <a class="nav-link @yield('active-ingreso')" href="{{url('prestaciones/ingresos')}}"><span>Ingresos</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link @yield('active-egreso')" href="{{url('prestaciones/egresos')}}"><span>Egresos</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link @yield('active-mensual')" href="{{url('prestaciones/mensual')}}"><span>Prestaciones mensuales</span></a>
                                </li>
                                <li>
                                    <a class="nav-link @yield('active-prestacionesrealizadas')" href="{{url('prestaciones/resumen')}}"><span>Prestaciones realizadas</span></a>
                                </li>
                                <li>
                                    <a class="nav-link @yield('active-rem')" href="{{url('prestaciones/rem')}}"><span>Tablas REM</span></a>
                                </li>
                                <li>
                                    <a class="nav-link @yield('active-rem7')" href="{{url('prestaciones/rem7')}}"><span>Tablas REM-7</span></a>
                                </li>
                            </ul>
                        </li>
                    </nav>

                    <nav id="sidebar">
                        <li class="nav-item">
                            <a class="nav-link @yield('active-pacientes')" href="#patients_Submenu" data-toggle="collapse" class="dropdown-toggle"><i class="material-icons">people</i><span>Pacientes</span></a>
                            <ul class="collapse list-unstyled" id="patients_Submenu">
                                <li>
                                    <a class="nav-link @yield('active-pacientesactivos')" href="{{url('pacientes')}}"><span>Pacientes</span></a>
                                </li>
                                @if($auth->rol == 1)
                                <li>
                                    <a class="nav-link @yield('active-pacientesinactivos')" href="{{url('pacientes/inactivos')}}"><span>Pacientes inactivos</span></a>
                                </li>
                                @endif
                            </ul>
                        </li>
                    </nav>

                    <nav id="sidebar">
                        <li class="nav-item">
                            <a class="nav-link @yield('active-funcionarios')" href="#functionarys_Submenu" data-toggle="collapse" class="dropdown-toggle"><i class="material-icons">people</i><span>Funcionarios</span></a>
                            <ul class="collapse list-unstyled" id="functionarys_Submenu">
                                <li>
                                    <a class="nav-link @yield('active-funcionariosactivos')" href="{{url('funcionarios')}}"><span>Funcionarios</span></a>
                                </li>
                                @if($auth->rol == 1)
                                <li>
                                    <a class="nav-link @yield('active-funcionariosinactivos')" href="{{url('funcionarios/inactivos')}}"><span>Funcionarios inactivos</span></a>
                                </li>
                                @endif
                            </ul>
                        </li>
                    </nav>

                    @if($auth->rol == 1)
                    <nav id="sidebar">
                        <li class="nav-item">
                            <a class="nav-link @yield('active-usuarios')" href="#users_Submenu" data-toggle="collapse" class="dropdown-toggle"><i class="material-icons">people</i><span>Usuarios</span></a>
                            <ul class="collapse list-unstyled" id="users_Submenu">
                                <li>
                                    <a class="nav-link @yield('active-usuariosactivos')" href="{{url('usuarios')}}"><span>Usuarios</span></a>
                                </li>
                                <li>
                                    <a class="nav-link @yield('active-usuariosinactivos')" href="{{url('usuarios/inactivos')}}"><span>Usuarios inactivos</span></a>
                                </li>
                            </ul>
                        </li>
                    </nav>

                    <nav id="sidebar">
                        <li class="nav-item">
                            <a class="nav-link @yield('active-ingresarpersonas')" href="#people_Submenu" data-toggle="collapse" class="dropdown-toggle"><i class="material-icons">group_add</i><span>Agregar personas</span></a>
                            <ul class="collapse list-unstyled" id="people_Submenu">
                                <li>
                                    <a class="nav-link @yield('active-ingresarpaciente')" href="{{url('registrar/paciente')}}"><span>Ingresar paciente</span></a>
                                </li>
                                <li>
                                    <a class="nav-link @yield('active-ingresarfuncionario')" href="{{url('registrar/funcionario')}}"><span>Ingresar funcionario</span></a>
                                </li>
                                <li>
                                    <a class="nav-link @yield('active-ingresarusuario')" href="{{url('registrar/usuario')}}"><span>Ingresar usuario</span></a>
                                </li>
                            </ul>
                        </li>
                    </nav>

                    <nav id="sidebar">
                        <li class="nav-item">
                            <a class="nav-link @yield('active-ingresardatos')" href="#data_Submenu" data-toggle="collapse" class="dropdown-toggle" id="test123"><i class="material-icons">add_box</i><span>Agregar datos</span></a>
                            <ul class="collapse list-unstyled" id="data_Submenu">
                                <li>
                                    <a class="nav-link @yield('active-registrar')" href="{{url('registrar')}}"><span>Datos simples </span></a>
                                </li>
                                <li>
                                    <a class="nav-link @yield('active-inactivos')" href="{{url('inactivo')}}"><span>Datos inactivos </span></a>
                                </li>
                                <li>
                                    <a class="nav-link @yield('active-asignarespecialidadactividad')" href="{{url('asignar/especialidad-actividad')}}"><span>Asignar especialidades a actividades</span></a>
                                </li>
                                <li>
                                    <a class="nav-link @yield('active-asignartipo')" href="{{url('asignar/especialidad-tipo')}}"><span>Asignar especialidades a tipo prestación</span></a>
                                </li>
                                <li>
                                    <a class="nav-link @yield('active-asignarespecialidad')" href="{{url('asignar/especialidad')}}"><span>Editar especialidades (funcionarios)</span></a>
                                </li>
                                <li>
                                    <a class="nav-link @yield('active-asignarespecialidadprestacion')" href="{{url('asignar/especialidad-prestacion')}}"><span>Editar especialidades (glosas)</span></a>
                                </li>
                            </ul>
                        </li>
                    </nav>
                    @endif

                    <nav id="sidebar">
                        <li class="nav-item">
                            <a class="nav-link @yield('active-editarmisdatos')" href="#info_Submenu" data-toggle="collapse" class="dropdown-toggle"><i class="material-icons">edit</i><span>Editar mis datos</span></a>
                            <ul class="collapse list-unstyled" id="info_Submenu">
                                <li>
                                    <a class="nav-link @yield('active-editarinformacion')" href="{{url('misdatos/edit')}}"><span>Editar mi información</span></a>
                                </li>
                                <li>
                                    <a class="nav-link @yield('active-editarcontraseña')" href="{{url('password/edit')}}"><span>Editar contraseña</span></a>
                                </li>
                                <li>
                                    <a class="nav-link @yield('active-editarhoras')" href="{{url('horas/edit')}}"><span>Editar mis horas</span></a>
                                </li>
                            </ul>
                        </li>
                    </nav>
                </ul>
            </nav>
        </div>
        <div class="page-content col-xs-12 col-sm-12">@yield('content')</div>
    </div>
</body>
<script>
    let icon = document.querySelector('.hamburger');
    let menu = document.querySelector('.menu');
    let one = document.querySelector('.one');
    let two = document.querySelector('.two');
    let three = document.querySelector('.three');


    let open = () => {
        one.style.transform = 'rotate(45deg)';
        two.style.transform = 'rotate(-45deg)';
        one.style.top = '12px';
        two.style.top = '12px';
        three.style.top = '24px';
        three.style.opacity = '0';

        menu.style.transform = 'translateX(0)';

        icon.classList.remove('closed');
        icon.classList.add('open');
        
        icon.removeEventListener('click', open);
        icon.addEventListener('click', close);
    };

    let close = () => {
        
        one.style.transform = 'rotate(0)';
        two.style.transform = 'rotate(0)';

        three.style.opacity = '1';
    
        one.style.top = '0';
        two.style.top = '12px';
        three.style.top = '24px';

        menu.style.transform = 'translateX(-100%)';

        icon.classList.remove('open');
        icon.classList.add('closed');
    
        icon.removeEventListener('click', close);
        icon.addEventListener('click', open);
    };


    if(icon.classList.contains('closed')){
        icon.addEventListener('click', open);
    } else {
        icon.addEventListener('click', close);
    }
</script>
   
</html>