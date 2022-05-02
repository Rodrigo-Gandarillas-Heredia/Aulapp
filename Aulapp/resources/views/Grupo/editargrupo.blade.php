@extends('plantilla')
@section("editar","grupoEdit")
@section("registrar","grupos")
@section("reporte","reporte_grupo")
@section("eliminar","eliminar-grupo")
@section('title', 'Grupo')
@section('Titulo')
<h3 text-center id="Titulo">Administracion de Grupos</h3>
@endsection
@section('Contenido formulario')

<div class="row">
<div >
  <div class="d-flex" id="formularioEditar">
    <form method="GET" action="" id="formulario">
      
      @csrf
      <h3 text-center>Editar Grupo</h3>
      <label  class="form-label " id="titulo">Ingrese el id del grupo que desea editar</label>
      <input type="text" id="buscador" class="form-control">
      <br>
      <button type="button" class="btn btn-dark btn-block btn-lg" data-toggle="button" aria-pressed="false" autocomplete="off" id="buscar">
       Buscar
      </button><br>
      <span id="carrera"></span><br>
      <span id="materia"></span><br>
      <span id="grupo"></span><br>
      <div id="docente"><select name="docente" id="asignado" class="form-select ed"></select></div>
        <button class="btn btn-dark btn-block btn-lg ed" id="botonRegistrar" type="submit">Guardar</button>
        <a href="" class="btn btn-danger btn-block btn-lg ed" id="botonRegistrar"
          type="button">Cancelar</a>
      </div>
    </form>
  </div>

</div>
@endsection
@section('js')
    <script>
        var buscar=document.getElementById("buscar");
        var buscador=document.getElementById("buscador");
        buscar.onclick=function(){
            var encontrado=0;
            var carrera=document.getElementById("carrera")
            var materia=document.getElementById("materia")
            var grupo=document.getElementById("grupo")
            @foreach ($grupos as $grupo )
                if('{{$grupo->id}}'==buscador.value ){
                     encontrado=1;
                     console.log("Encontrado")
                     grupo.innerHTML="Grupo: {{$grupo->nombre}}"
                     materia.innerHTML="Materia: {{$grupo->asignacionDocente->materia_carrera->materia->nombre_materia}}"
                     carrera.innerHTML="Carrera: {{$grupo->asignacionDocente->materia_carrera->carrera->Nombre}}"
                     formulario.action="{{route('grupo-update', ['id'=>$grupo->asignacionDocente->id])}}"
                     ed=document.getElementsByClassName("ed")
                     for(var i=0;i<ed.length;i++){
                        ed[i].style.display="block";
                         }
                         var docente=document.getElementById("docente")
                    if('{{$grupo->asignacionDocente->user_rol_id}}'!=""){
                        @foreach ($urs as $ur )
                            @foreach ($docentes as $docente )
                                if("{{$grupo->asignacionDocente->user_rol_id}}"== "{{$ur->id}}" && "{{$docente->id}}"=="{{$ur->usuario_id}}"){
                                    docente.innerHTML="Docente: {{$docente->Nombre}} {{$docente->Apellido}}"
                                }
                            @endforeach
                        @endforeach
                    }else{
                        var asignado=document.getElementById("asignado")
                        asignado.innerHTML+="<option value='0'>Por designar</option>"
                        @foreach ($docentes as $docente)
                            @foreach ($urs as $ur)
                                @foreach ($ads as $ad)
                                    if('{{$grupo->asignacionDocente->materia_carreras_id}}'=='{{$ad->materia_carreras_id}}' && "{{$ad->user_rol_id}}"=='{{$ur->id}}' && '{{$ur->usuario_id}}'=='{{$docente->id}}'){
                                        asignado.innerHTML+="<option value='{{$ur->id}}'>{{$docente->Nombre}}</option>"
                                    }
                                @endforeach
                            @endforeach
                        @endforeach
                    }

                } 
            @endforeach
            if(encontrado==0){
                Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'No se encontro ningun grupo con ese id',
    })
            }
        }
    </script>
@endsection