<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\asignacionDocentes;
use App\Models\Aula;
use App\Models\AulaAsignada;
use App\Models\gestion;
use App\Models\Materia;
use App\Models\nuevasnotificacion;
use App\Models\reserva;
use App\Models\UserRol;
use App\Notifications\NotificacionReserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class reservaController extends Controller
{

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
 }

 public function registro()
 {
  $usuario = Auth::user();
  $gestion=gestion::where("estado",1)->get();
  $ur = UserRol::where("usuario_id",$usuario->id)->get();
  $not= nuevasnotificacion::where("user_rol_id",$ur[0]->id)->get();
  $cantidad=0;
       if($not!="[]"){
            $cantidad=$not[0]->cantidad_not;
        }
  $materias= asignacionDocentes::join("user_rols","user_rols.id","=","asignacion_docentes.user_rol_id")->where("user_rols.usuario_id",$usuario->id)->where("asignacion_docentes.gestion_id",$gestion[0]->id)->join("grupos","grupos.id",'=','asignacion_docentes.grupo_id')->join("materia_carreras","materia_carreras.id","=","grupos.materia_carrera_id")->join("materias","materias.id","=","materia_carreras.materia_id")->select("materias.nombre_materia")->get()->unique("nombre_materia");

  $ads = asignacionDocentes::where("gestion_id",$gestion[0]->id)->get();

  return view('registrarreserva', ['ads' => $ads, 'materias'=>$materias, 'usuario'=>$usuario,"not" =>$cantidad]);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  //
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(Request $request)
 {
  //
  $reserva                 = new reserva();
  $reserva->motivo         = $request->motivo;
  $reserva->estado         = "enviado";
  $reserva->fecha_examen   = $request->fecha;
  $reserva->hora_inicio    = $request->horario;
  $reserva->hora_fin       = $request->fechaf;
  $reserva->cantE          = $request->cantidad;
  $reserva->grupos         = $request->grupos;
  $reserva->docentes       = $request->docentes;
  $reserva->materia        = $request->materia;
  $reserva->user_rol_id    = $request->id;
  $reserva->motivo_rechazo = "";
 $reserva->save();
  $buscar_usuario=UserRol::where("rol_id",1)->get();
  $buscar_not=nuevasnotificacion::where("user_rol_id",$buscar_usuario[0]->id)->get();
  echo($buscar_not);
  if($buscar_not=="[]"){
    $notificacion=new nuevasnotificacion();
    $notificacion->user_rol_id=$buscar_usuario[0]->id;
    $notificacion->cantidad_not=1;
    $notificacion->save();
    echo("nuevo");
  }else{
    $buscar_not[0]->cantidad_not=$buscar_not[0]->cantidad_not+1;
    $buscar_not[0]->save();
    echo("encontrado");
  }
  return redirect()->route('registro_reserva')->with('actualizar', 'ok');
 }

 /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */

 public function respuesta(Request $request, $id, $estado)
 {
  $aulas_asignadas = [];
  if ($estado == 0) {
   $request->validate([
    'motivo_rechazo' => 'required',
   ]);
   $rechazado                 = reserva::find($id);
   $rechazado->estado         = "rechazado";
   $rechazado->motivo_rechazo = $request->motivo_rechazo;
   $rechazado->save();
   Notification::route('mail', $rechazado->user_rol->usuario->Email)->notify(new NotificacionReserva($rechazado)); /* para usar cuando se guarde la reserva */

  } else {
   $aceptado         = reserva::find($id);
   $aceptado->estado = "aceptado";
   $aceptado->save();
   $aulas = explode(",", $request->aulas_nombres);
   for ($i = 0; $i < sizeof($aulas); $i++) {
    $aula_asignada             = new AulaAsignada();
    $sql                       = Aula::where("nombre", $aulas[$i])->value("id");
    $aula_asignada->reserva_id = $id;
    $aula_asignada->aula_id    = $sql;
    $aula_asignada->save();
    $aulas_asignadas[] = $aula_asignada;
   }
   Notification::route('mail', $aceptado->user_rol->usuario->Email)->notify(new NotificacionReserva($aceptado, $aulas_asignadas)); /* para usar cuando se guarde la reserva */
   
}
$buscar_reserva=reserva::find($id);

$buscar_not=nuevasnotificacion::where("user_rol_id",$buscar_reserva->user_rol_id)->get();
  echo($buscar_not);
  if($buscar_not=="[]"){
    $notificacion=new nuevasnotificacion();
    $notificacion->user_rol_id=$buscar_reserva->user_rol_id;
    $notificacion->cantidad_not=1;
    $notificacion->save();
    echo("nuevo");
  }else{
    $buscar_not[0]->cantidad_not=$buscar_not[0]->cantidad_not+1;
    $buscar_not[0]->save();
    echo("encontrado");
  }
return redirect()->route('respuestaAdmin')->with('actualizar', 'ok');
}

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $reserva        = reserva::find($id);
    $aulasAsignadas = AulaAsignada::all();
    return view('respuesta', compact('reserva', 'aulasAsignadas'));
  }

  public function reportePeticiones()
  {
    $usuario = Auth::user();
    $ur = UserRol::where("usuario_id",$usuario->id)->get();
    $not= nuevasnotificacion::where("user_rol_id",$ur[0]->id)->get();
    if($not!="[]"){
      $not[0]->cantidad_not=0;
      $not[0]->save();
    }
    $reservas = reserva::orderBy('created_at','DESC')->get();
    return view('bandeja_administrador', compact('reservas'));
  }



  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }
}