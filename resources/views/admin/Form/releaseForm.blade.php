@extends('admin.Views.registerMain')
@section('title','Registrar alta')
@section('active-ingresardatos','active')
@section('active-registrar','active')

@section('sub-content')
<h1>Registrar Alta</h1>
<div class="div-full">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif
    <form method="post" action="{{ url('registrar/alta') }}">
        @csrf
        <div class="form-group">
            <div class="form-row">
                <div class="col-6">
                    <input type="text" class="form-control {{ $errors->has('descripcion') ? ' is-invalid' : '' }}" value="{{ old('descripcion') }}" id="descripcion" name="descripcion" placeholder="Motivo del Alta">
                    <br>
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
                                    <th style="width: 70%;">Altas</th>
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
    var table = <?php echo json_encode($table); ?>;
    document.getElementById('data_Submenu').className += ' show';
</script>
@endsection