@extends('layouts.main')
@section('title','Paciente')
@section('active-pacientes','active')

@section('content')
<h1>Paciente</h1>
<div>
    <p><b>Nombre: </b>Felipe Rojas</p>
    <p><b>Fecha de nacimiento: </b>20/06/1978</p>
    <p><b>Diagnostico:</b><br>Lorem ipsum dolor sit Lorem ipsum dolor, sit amet consectetur adipisicing elit. Nobis animi aliquam voluptas amet? Recusandae maiores magni similique assumenda, nemo quas! Iste facilis veritatis odit doloremque consequuntur nobis nulla temporibus suscipit. amet consectetur adipisicing elit. Nam atque amet architecto doloribus tempora harum, aliquam a quasi alias voluptate? Nobis maiores soluta eos laborum recusandae incidunt consequuntur reiciendis ullam?</p>
    <p><b>Estado actual: </b>En tratamiento</p>
    <p><b>Fecha de ingreso: </b>13/10/2018</p>
    <p><b>Fecha de salida: </b>-</p>
    <button type="button" class="btn btn-primary">Añadir prestación</button>
    <button type="button" class="btn btn-primary">Editar información</button>
</div>
@endsection