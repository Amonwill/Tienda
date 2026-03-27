<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProductoController extends Controller
{
    public function index()
    {
        // Llamamos al procedimiento 'ver' de tu query
        $productos = DB::select('EXEC sp_gestion_inventario @accion = ?', ['ver']);
        return Inertia::render('Products/Index', [
            'products' => $productos
        ]);
    }

    public function store(Request $request)
    {
        // Usamos el procedimiento 'agregar'
        DB::statement('EXEC sp_gestion_inventario 
            @accion = ?, @nombre = ?, @desc = ?, @id_prov = ?, 
            @p_compra = ?, @p_venta = ?, @cant = ?, @num_lote = ?, @caducidad = ?', 
            [
                'agregar', $request->nombre, $request->descripcion, $request->id_proveedor,
                $request->precio_compra, $request->precio_venta, $request->cantidad,
                $request->num_lote, $request->fecha_caducidad
            ]
        );
        return redirect()->back()->with('message', 'Producto agregado con éxito');
    }
}