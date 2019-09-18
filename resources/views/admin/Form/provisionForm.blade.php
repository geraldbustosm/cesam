@extends('layouts.main')
@section('title','Prestacion')
@section('active-ingresardatos','active')
@section('active-ingresarprestacion','active')

@section('content')
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<h1>Ingresar Prestacion</h1>
<div class="div-full">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif
    <form method="post" action="{{ url('registrar/prestacion') }}">
        @csrf
        <div class="form-group">
            <div class="form-row">
                <div class="col">

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col">
                                <input type="text" class="form-control {{ $errors->has('glosa') ? ' is-invalid' : '' }}" value="{{ old('glosa') }}" id="glosa" name="glosa" placeholder="Glosa Trasadora">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col">
                                <input type="text" class="form-control {{ $errors->has('codigo') ? ' is-invalid' : '' }}" value="{{ old('codigo') }}" id="apellido1" name="codigo" placeholder="Codigo">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <select class="form-control" name="edadInf" required>
                            <option selected disabled>Por favor seleccione el rango de edad inferior</option>
                            @for ($i = 0; $i <= 120; $i++) <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                        </select>
                    </div>

                    <div class="form-group">
                        <select class="form-control" name="edadSup" required>
                            <option selected disabled>Por favor seleccione el rango de edad superior</option>
                            @for ($i = 0; $i <= 120; $i++) <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                        </select>
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control {{ $errors->has('frecuencia') ? ' is-invalid' : '' }}" value="{{ old('profesion') }}" id="frecuencia" name="frecuencia" placeholder="Frecuencia">
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control {{ $errors->has('ps_fam') ? ' is-invalid' : '' }}" value="{{ old('ps_fam') }}" id="ps_fam" name="ps_fam" placeholder="ps-fam">
                    </div>

                    <div class="form-group">
                        <select class="form-control" name="type" required>
                            <option selected disabled>Por favor seleccione el tipo de prestación</option>
                            @foreach($type as $type)
                            <option value="{{ $type->id}}">{{ $type->descripcion}}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>

                <div class="col">
                    <div>
                        <input class="form-control" id="searchbox" type="text" placeholder="Búsqueda...">
                    </div><br>

                    <div class="">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 3%;">#</th>
                                    <th style="width: 70%;">Prestaciones</th>
                                    <th style="width: 10%;">Código</th>
                                    <th style="width: 10%;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                <!-- Fill on js -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="div-full">
                        <ul class="pagination justify-content-center" id="paginate">
                            <!-- Generate in patientFilter.js->generatePaginationNum(); -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- Getting data -->
<script>
    var fullArray = <?php echo json_encode($data); ?>;
    console.log(fullArray);
    document.getElementById('data_Submenu').className += ' show';
</script>
<!-- Adding script using on this view -->
<script type="text/javascript" src="{{asset('js/pagination.js')}}"></script>
<script type="text/javascript" src="{{asset('js/actionButtons.js')}}"></script>
<script type="text/javascript" src="{{asset('js/tableGenerator.js')}}"></script>
@endsection