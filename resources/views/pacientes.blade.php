@extends('layout')
@section('title','Pacientes')
@section('user', 'David')
@section('active-pacientes','active')
@section('content')
<h1>Pacientes</h1>
<div>
  <input class="form-control" id="searchbox" type="text" placeholder="Felipe Ruiz">
</div>
<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">First</th>
            <th scope="col">Last</th>
            <th scope="col">Handle</th>
            <th scope="col">Descripcion</th>
            <th scope="col">Acciones</th>
        </tr>
    </thead>
    <tbody>
    <tr>
      <th scope="row">1</th>
      <td>Mark</td>
      <td>Otto</td>
      <td>@mdo</td>
      <td>@fat</td>
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
      <td>@fat</td>
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
      <td>@fat</td>
      <td>
      <a class="" href="#"><i title="Ver ficha" class="material-icons">description</i></a>
        <a class="" href="#"><i title="Editar" class="material-icons">create</i></a>
        <a class="" href="#"><i title="Borrar" class="material-icons">delete</i></a></td>
    </tr>
  </tbody>
</table>
@endsection