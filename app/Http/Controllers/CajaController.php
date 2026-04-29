<?php
namespace App\Http\Controllers;

use App\Models\Caja_General;
use App\Models\Cajas_Cat;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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
            'Fecha_Apertura' => now(), 
            'Estado_Caja' => 1,
            'Ingresos_Totales' => 0,
            'Egresos_Totales' => 0
        ]);
        return redirect()->route('caja.index'); 
    }

    // --- ESTA FUNCIÓN CIERRA LA CAJA REALMENTE ---
    public function cerrarTurnoAutomatico()
    {
        DB::table('Caja_General')
            ->where('Estado_Caja', 1)
            ->update([
                'Estado_Caja' => 0,
                'Fecha_Cierre' => now()
            ]);

        return redirect()->route('caja.index')->with('success', 'Caja cerrada correctamente');
    }

    public function descargarCorteCaja()
    {
        // Solo generamos el PDF de las sesiones de hoy
        $sesiones = DB::select("
            SELECT cg.ID_Session, cc.Nombre_Caja, cg.Saldo_Inicial, 
                   cg.Ingresos_Totales, cg.Egresos_Totales, cg.Fecha_Apertura, cg.Estado_Caja
            FROM Caja_General cg 
            JOIN Cajas_Cat cc ON cg.ID_Caja_Fisica = cc.ID_Caja_Fisica 
            WHERE CAST(cg.Fecha_Apertura AS DATE) = CAST(GETDATE() AS DATE)
        ");

        foreach ($sesiones as $s) {
            $s->productos = DB::select("
                SELECT p.Nombre_Producto, SUM(dv.Cantidad) as total_cant, 
                       SUM(dv.Cantidad * dv.Precio_Unitario) as subtotal_prod
                FROM Detalle_Venta dv 
                JOIN Productos_Cat p ON dv.ID_Producto = p.ID_Producto 
                JOIN Ventas v ON dv.ID_Venta = v.ID_Venta 
                WHERE v.ID_Caja = ? 
                GROUP BY p.Nombre_Producto", [$s->ID_Session]);
        }

        $pdf = Pdf::loadView('pdf.corte_caja', compact('sesiones'));
        return $pdf->download('Corte_Diario_LaModerna_'.date('d-m-Y').'.pdf');
    }
}