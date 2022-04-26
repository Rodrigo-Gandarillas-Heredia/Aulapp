@extends('plantilla')
@section('title', 'Seccion')
@section('Titulo')
<h3 text-center>Administracion de grupos </h3>
@endsection
@section('Contenido formulario')
<div id="C_tabla">
      <h3 id="T_tabla">Lista de grupos</h3>
      <table class="table">
      
            <thead>                
                  <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Materia</th>
                        <th scope="col">Carrera</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellido</th>
                      
                    
                  </tr>
            </thead>
            <tbody>
                    @foreach($grupos as $grupo)
                   <tr>
                         
                         <td>{{$grupo->nombre}}</td>
                         <td>{{$grupo->asignacionDocente->materia_carrera->materia->nombre_materia}}</td>
                         <td>{{$grupo->asignacionDocente->user_rol->usuario->Nombre}}</td>
                         <td>{{$grupo->asignacionDocente->materia_carrera->carrera->Nombre}}</td>
                         <td>{{$grupo->asignacionDocente->user_rol->usuario->Apellido}}</td>
                         <td>
                               <div><a href="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    </svg>
                               </a>
                               </div>
                             
                         </td>
                         
                   </tr>   
                   @endforeach  
                  
            </tbody>
      </table>
</div>
@endsection