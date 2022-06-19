<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSeccion;
use App\Models\Aula;
use App\Models\Seccion;
use Illuminate\Http\Request;

class SeccionController extends Controller
{
 public function vistaRegistro()
 {
  return view('Seccion.registrar_seccion_de_aula');
 }

 public function vistaEditar()
 {
  $secciones = Seccion::all();
  return view('Seccion.editar_seccion', ['secciones' => $secciones]);

 }
 public function reporte()
 {
  $sections = Seccion::all();
  return view('Seccion.reporte_seccion', compact('sections'));
 }

 public function registro(StoreSeccion $request)
 {

  $seccion              = new Seccion();
  $seccion->nombre      = $request->nombre;
  $seccion->descripcion = $request->descripcion;
  $seccion->save();

  return redirect()->route('secciones')->with('registrar', 'ok');
 }

 public function show($id)
 {

 }

 public function editar(Request $request, $id)
 {
  $section = Seccion::find($id);
  $request->validate([
   'nombre'      => 'required|min:10|max:50|regex:/^[a-zA-Z\s áéíóúÁÉÍÓÚñÑ 0-9]+$/u|unique:sections,nombre,' . $section->id,
   'descripcion' => 'required|min:10|max:50',
  ]);

  $section->nombre      = $request->nombre;
  $section->descripcion = $request->descripcion;
  $section->estado      = $request->estadoE;
  $section->save();
  return redirect()->route('seccion_edit')->with('actualizar', 'ok');

 }
 public function busqueda(Request $request)
 {
  $nombre = $request->search;
  try {

   $section = Seccion::query();

   if ($request->has('search')) {
    $section->where('nombre', 'like', $request->search);

   }
   $sections = $section->get();
   return view('Seccion.eliminar_seccion', compact('sections'));

  } catch (\Throwable $th) {

   return redirect()->route('eliminar-seccion')->with('buscar', 'error');

  }}

 public function estado(Request $request, $section)
 {
  $section     = Seccion::find($section);
  $aulas       = Aula::all();
  $sizeSection = 0;

  foreach ($aulas as $aula) {
   if ($aula->section_id == $section->id && $aula->estado == 1) {
    $sizeSection++;
   }
  }

  if ($sizeSection == 0) {
   $section->where('id', $request->section)->update(['estado' => false]);
   return redirect()->route('eliminar-seccion')->with('eliminar', 'ok');
  } else {
   return redirect()->route('eliminar-seccion')->with('eliminar', 'error');
  }
 }

}