<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class VentaController extends Controller
{
    public function index()
    {
        // Obtenemos los productos activos para mostrarlos en la vista
        $productos = DB::table('Productos_Cat')->where('Producto_Activo', 1)->get();
        return Inertia::render('Sales/Index', [
            'products' => $productos
        ]);
    }

    public function store(Request $request)
    {
        try {
            // Ejecutamos tu procedimiento para registrar la venta, pasando los parámetros necesarios
            DB::select('EXEC sp_registrar_venta @id_cliente = ?, @pago = ?, @id_producto = ?, @cantidad = ?, @id_session = ?', [
                $request->id_cliente,
                $request->pago_con,
                $request->id_producto,
                $request->cantidad,
                $request->id_session 
            ]);

            return redirect()->back()->with('success', 'Venta realizada');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}