@extends('layout')
@section('title','Pacientes')
@section('user', 'David')
@section('active-pacientes','active')
@section('content')
<h1>Pacientes</h1>
<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col-2">#</th>
            <th scope="col-3">First</th>
            <th scope="col-5">Last</th>
            <th scope="col-1">Handle</th>
            <th scope="col-1">Acciones</th>
        </tr>
    </thead>
    <tbody>
    <tr>
      <th scope="row">1</th>
      <td>Mark</td>
      <td>Otto</td>
      <td>@mdo</td>
      <td>
      <a class="" href="#"><i title="Ver ficha" class="material-icons">description</i></a>
        <a class="" href="#"><i title="Editar" class="material-icons">create</i></a>
        <a class="" href="#"><i title="Borrar" class="material-icons">delete</i></a></td>
    </td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Jacob</td>
      <td>Thornton</td>
      <td>@fat</td>
      <td>
      <a class="" href="#"><i title="Ver ficha" class="material-icons">description</i></a>
        <a class="" href="#"><i title="Editar" class="material-icons">create</i></a>
        <a class="" href="#"><i title="Borrar" class="material-icons">delete</i></a></td>
    </td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td>Larry</td>
      <td>the Bird</td>
      <td>@twitter</td>
      <td>
        <a class="" href="#"><i title="Ver ficha" class="material-icons">description</i></a>
        <a class="" href="#"><i title="Editar" class="material-icons">create</i></a>
        <a class="" href="#"><i title="Borrar" class="material-icons">delete</i></a></td>
    
    </td>
    </tr>
    <tr>
      <th scope="row">4</th>
      <td>Larry</td>
      <td>the Bird</td>
      <td>@twitter</td>
      <td>
      <a class="" href="#"><i title="Ver ficha" class="material-icons">description</i></a>
        <a class="" href="#"><i title="Editar" class="material-icons">create</i></a>
        <a class="" href="#"><i title="Borrar" class="material-icons">delete</i></a></td>
    </tr>
  </tbody>
</table>
@endsection