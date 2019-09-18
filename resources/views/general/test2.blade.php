@extends('layouts.main')
@section('title','Registrar nueva atención')
@section('active-ingresaratencion','active')


@section('content')
<h1>Test2</h1>
<script>
    var fullArray = <?php echo json_encode($stage); ?>;
    console.log(fullArray);
</script>
@endsection