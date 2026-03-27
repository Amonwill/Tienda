<?php
namespace App\Http\Controllers;
use App\Models\Caja_General;
use App\Models\Cajas_Cat;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class CajaController extends Controller
{
    //Vista para abrir caja
    public function index()
    {
        return Inertia::render('Caja/Index', [
            'cajas_fisicas' => Cajas_Cat::where('Activa', 1)->get(),
            'sesion_activa' => Caja_General::where('Estado_Caja', 1)->first()
        ]);
    }

    //Funcion para abrir l caja
    public function abrirCaja(Request $request)
    {
        Caja_General::create([
            'ID_Caja_Fisica' => $request->id_caja_fisica,
            'Saldo_Inicial' => $request->saldo_inicial,
            'Fecha_Apertura' => now(), 
            'Estado_Caja' => 1,
            'Ingresos_Totales' => 0,
            'Egresos_Totales' => 0
        ]);
        return redirect()->route('ventas.index'); 
    }

    // Vista para mostrar el corte de caja
    public function showCorte()
    {
        $sesionActiva = Caja_General::where('Estado_Caja', 1)->first();
        if (!$sesionActiva) {
            return redirect()->route('caja.index')->with('error', 'No hay ninguna caja abierta actualmente.');
        }
        return Inertia::render('Caja/Corte', [
            'sesion' => $sesionActiva,
            'ventasResumen' => [] 
        ]);
    }

    // Función para cerrar la caja
    public function cerrarCaja(Request $request)
    {
        $sesion = Caja_General::findOrFail($request->id_session);
        
        $sesion->update([
            'Fecha_Cierre' => now(),
            'Estado_Caja' => 0,
            'Egresos_Totales' => $sesion->Egresos_Totales, 
            'Ingresos_Totales' => $sesion->Ingresos_Totales 
        ]);
        return redirect()->route('dashboard')->with('success', 'Turno cerrado y guardado con éxito.');
    }
}