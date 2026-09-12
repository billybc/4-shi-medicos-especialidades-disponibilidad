<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Especialidad;
use App\Models\Disponibilidad; // Cambia este nombre según la tabla/modelo que maneje tus horarios

class ChatbotController extends Controller
{
    public function responder(Request $request)
    {
        $opcion = $request->input('opcion');

        switch ($opcion) {
            case 'especialidades':
                $especialidades = Especialidad::pluck('nombre')->implode('<br>• ');
                return response()->json([
                    'respuesta' => "<b>Especialidades disponibles:</b><br>• " . $especialidades
                ]);

            case 'medicos':
                $medicos = Doctor::with('especialidad')->get()->map(function($doc) {
                    return "<b>Dr. {$doc->nombre} {$doc->apellido}</b> ({$doc->especialidad->nombre})";
                })->implode('<br>• ');

                return response()->json([
                    'respuesta' => "<b>Nuestros Médicos:</b><br>• " . $medicos
                ]);

            case 'disponibilidad':
                $horarios = Disponibilidad::with('doctor')->where('activo', true)->get()->map(function($h) {
                    return "<b>Dr. {$h->doctor->apellido}:</b> {$h->dia} de {$h->hora_inicio} a {$h->hora_fin}";
                })->implode('<br>• ');

                return response()->json([
                    'respuesta' => "<b>Horarios y Disponibilidad:</b><br>• " . ($horarios ?: 'Consulte directamente en recepción.')
                ]);

            default:
                return response()->json([
                    'respuesta' => 'Por favor selecciona una opción válida.'
                ]);
        }
    }
}