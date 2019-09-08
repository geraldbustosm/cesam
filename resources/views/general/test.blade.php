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

  <title>Cesam - Testing</title>
</head>

<ul class="nav">
  <li class="nav-item">
    <a class="nav-link active" href="http://localhost:8000/testing">Diagnósticos</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="http://localhost:8000/registrarsexo">Géneros</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="http://localhost:8000/registrarprevision">Previsiones</a>
  </li>
  <li class="nav-item">
    <a class="nav-link disabled" href="http://localhost:8000/">Procedencias</a>
  </li>
</ul>

<body>

  <h1>TEST</h1>

  <div class="div-full">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
      {{ session('status') }}
    </div>
    @endif
    <form method="post" action="{{ url('testing') }}">
      @csrf
      <br>
      <div class="form-group">
        <div class="form-row">
          <div class="col-6">
            <input type="text" class="form-control {{ $errors->has('descripcion') ? ' is-invalid' : '' }}" value="{{ old('descripcion') }}" id="descripcion" name="descripcion" placeholder="Tipo de diagnostico">
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
                    <th style="width: 70%;">Diagnósticos</th>
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
  </script>
  <!-- Adding script using on this view -->
  <script type="text/javascript" src="{{asset('js/testing.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/pagination.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/actionButtons.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/tableGenerator.js')}}"></script>

  <script src="{{asset('js/popper.min.js')}}"></script>
  <script src="{{asset('js/bootstrap.min.js')}}"></script>
  <script src="{{asset('js/jquery-3.4.0.min.js')}}"></script>

</body>

</html>