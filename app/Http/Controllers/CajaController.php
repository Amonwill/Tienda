<?php
namespace App\Http\Controllers;
use App\Models\Caja_General;
use App\Models\Cajas_Cat;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CajaController extends Controller
{
    public function index()
    {
        return Inertia::render('Caja/Index', [
            'cajas_fisicas' => Cajas_Cat::where('Activa', 1)->get(),
            'sesion_activa' => Caja_General::where('Estado_Caja', 1)->first()
        ]);
    }

    public function abrirCaja(Request $request)
    {
        Caja_General::create([
            'ID_Caja_Fisica' => $request->id_caja_fisica,
            'Saldo_Inicial' => $request->saldo_inicial,
            'Estado_Caja' => 1
        ]);
        return redirect()->back();
    }
    public function cerrarCaja(Request $request)
    {
        $sesion = Caja_General::findOrFail($request->id_session);
        // Actualizamos la sesión con fecha de cierre y estado 0
        $sesion->update([
            'Fecha_Cierre' => now(),
            'Estado_Caja' => 0,
            // Aquí podrías guardar también el saldo_final_real si quieres auditar la diferencia
        ]);
        return redirect()->route('dashboard')->with('success', 'Turno cerrado con éxito.');
    }
}