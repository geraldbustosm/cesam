@extends('layouts.main')
@section('title','Registrar programa')
@section('active-ingresardatos','active')
@section('active-ingresarprograma','active')

@section('content')
<h1>Registrar Programa</h1>
<div class="div-full">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif
    <form method="post" action="{{ url('registrarprograma') }}">
        @csrf
        <div class="form-group">
            <div class="form-row">
                <div class="col-6">
                    <select class="form-control" name="programa_id" required>
				        <option selected disabled>Por favor seleccione un programa </option>
				        @foreach($program as $programa)
				        <option value="{{ $programa->id}}">{{ $programa->descripcion}}</option>
				        @endforeach
			        </select>
                    <input type="text" class="form-control {{ $errors->has('descripcion') ? ' is-invalid' : '' }}" value="{{ old('descripcion') }}" id="descripcion" name="descripcion" placeholder="Tipo de programa">
                    <br>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
                <div class="col">
                    <div class="">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 3%;">#</th>
                                    <th style="width: 70%;">Programas</th>
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
    document.getElementById('data_Submenu').className += ' show';
</script>
<!-- Adding script using on this view -->
<script type="text/javascript" src="{{asset('js/pagination.js')}}"></script>
<script type="text/javascript" src="{{asset('js/actionButtons.js')}}"></script>
<script type="text/javascript" src="{{asset('js/tableGenerator.js')}}"></script>
@endsection