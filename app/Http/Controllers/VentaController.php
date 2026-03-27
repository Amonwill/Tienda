<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\Caja_General;

class VentaController extends Controller
{
    // Vista de ventas
    public function index()
    {
        $productos = DB::table('Productos_Cat')->where('Producto_Activo', 1)->get();
        $sesion = Caja_General::where('Estado_Caja', 1)->first(); 
        return Inertia::render('Sales/Index', [
            'products' => $productos,
            'activeSession' => $sesion 
        ]);
    }

    // Registrar venta a través de procedimiento almacenado y transacción
    public function store(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                foreach ($request->items as $item) {
                    DB::select('EXEC sp_registrar_venta @id_cliente = ?, @pago = ?, @id_producto = ?, @cantidad = ?, @id_session = ?', [
                        $request->id_cliente,
                        $request->pago_con,
                        $item['ID_Producto'], 
                        $item['quantity'],   
                        $request->id_session
                    ]);
                }
            });

            return redirect()->back()->with('success', 'Venta completa registrada');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}