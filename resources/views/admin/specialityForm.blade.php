@extends('layouts.main')
@section('title','Registrar Especialidad')
@section('active-ingresardatos','active')
@section('active-ingresarespecialidad','active')

@section('content')
<h1>Registrar Especialidad Medica</h1>
<div class="div-full">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif
    <form method="post" action="{{ url('registrarespecialidad') }}">
        @csrf
        <div class="form-group">
            <div class="form-row">
                <div class="col-6">
                    <input type="text" class="form-control {{ $errors->has('descripcion') ? ' is-invalid' : '' }}" value="{{ old('descripcion') }}" id="descripcion" name="descripcion" placeholder="Especialidad Medica">
                    <br>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
                <div class="col">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 3%;">#</th>
                                <th style="width: 70%;">Especialidades</th>
                                <th style="width: 10%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            <!-- Fill on js -->
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
    var Arr = <?php echo json_encode($specialitys); ?>;
</script>
<script type="text/javascript" src="{{asset('js/tableGenerator.js')}}"></script>
@endsection