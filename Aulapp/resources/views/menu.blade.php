@extends('header')
@section("notificacion")
  @if($rol->id == 1)
      bandeja_administrador
  @else
      bandeja_docente
  @endif
@endsection
@section('Titulo')
    <h3>Menu - {{ $rol->nombre }}</h3>
@endsection
@section('Contenido')
    </div>
    <div id="funcionalidades">
        @foreach($privilegios as $privilegio)
            <x-menu.funcionalidad
                :nombre="$privilegio->funcionalidad->nombre"
                :icono="$privilegio->funcionalidad->icono"
                :ruta="$privilegio->funcionalidad->ruta"
            />
        @endforeach
    </div>
    <div class="card mb-3" style="max-width: 540px;" id="presentacion">
        <div class="row g-0">
          <div class="col-md-6">
            <img src="{{asset('Imagenes/admi.svg')}}" class="img-fluid rounded-start" alt="..." id="admi">
          </div>
          <div class="col-md-6">
            <div class="card-body">
              <h5 class="card-title">{{ $rol->nombre }}</h5>
              <p class="card-text">Con Aulapp ahora podrás administrar las solicitudes y asignaciones de reservas de aulas de la Facultad de Ciencias y Tecnologia de la Universidad Mayor de San Simón</p>
              <p class="card-text"><small class="text-muted">Aqui tienes las diferentes funciones que te ofrece Aulapp</small></p>
            </div>
          </div>
        </div>
      </div>
      
  <script>localStorage.setItem("usuario",{{session('id')}});
 
  </script>

@endsection
</body>
</html>
