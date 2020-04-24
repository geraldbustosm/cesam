@extends('layouts.main')
@section('title','Registrar usuario')
@section('active-ingresarpersonas','active')
@section('active-ingresarusuario','active')

@section('content')
<h1>Registrar usuario</h1>
<div class="div-full">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form method="post" action="{{ url('registrar/usuario') }}" name="onSubmit" id="onSubmit">
        @csrf
        <!-- Nickname -->
        <div class="form-group">
            <input type="text" class="form-control {{ $errors->has('nick') ? ' is-invalid' : '' }}" value="{{ old('nick') }}" id="nick" name="nick" placeholder="Nombre de usuario">
        </div>
        <!-- Names -->
        <div class="form-group">
            <div class="form-row">
                <div class="col">
                    <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}" id="name" name="name" placeholder="Primer Nombre">
                </div>
                <div class="col">
                    <input type="text" class="form-control {{ $errors->has('last_name') ? ' is-invalid' : '' }}" value="{{ old('last_name') }}" id="last_name" name="last_name" placeholder="Apellido Paterno">
                </div>
                <div class="col">
                    <input type="text" class="form-control {{ $errors->has('second_last_name') ? ' is-invalid' : '' }}" value="{{ old('second_last_name') }}" id="second_last_name" name="second_last_name" placeholder="Apellido Materno">
                </div>
            </div>
        </div>
        <!-- UID -->
        <div class="form-group">
            <input type="text" class="form-control {{ $errors->has('rut') ? ' is-invalid' : '' }}" value="{{ old('rut') }}" id="rut" name="rut" placeholder="Rut o pasaporte">
        </div>
        <!-- Email -->
        <div class="form-group">
            <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" id="email" name="email" placeholder="Correo">
        </div>
        <!-- Role -->
        <div class="form-group">
            <select id="rol" name="rol" class="form-control">
                <option disabled selected>Rol de usuario</option>
                <option value="1">Administrador</option>
                <option value="2">Funcionario</option>
                <option value="3">Secretaria</option>
            </select>
        </div>
        <!-- Password -->
        <div class="form-group">
            <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" autocomplete="new-password" id="password" name="password" placeholder="Contraseña">
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password" placeholder="Confirmar contraseña">
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div>

<!-- Scripts for view -->
<script src="{{asset('js/rutValidator.js')}}"></script>
<script>
    document.getElementById('people_Submenu').className += ' show';
    // Submit listener
    const form = document.getElementById('onSubmit');
    form.addEventListener('submit', async (e) => {
        // No reaload
        e.preventDefault();
        await CheckRUT(document.getElementById('rut')).then(res => {
            if (res) document.onSubmit.submit()
            else Swal.fire('Error!', `El rut no es válido`, 'error');
        })
    });
</script>
@endsection