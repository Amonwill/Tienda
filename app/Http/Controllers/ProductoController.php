<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProductoController extends Controller
{
    public function index()
    {
        // Obtenemos productos y la lista de nombres de proveedores existentes
        $productos = DB::select('EXEC sp_gestion_inventario @accion = ?', ['ver']);
        $proveedores = DB::select('SELECT ID_Proveedor, Nombre_Proveedor FROM Proveedores_Cat WHERE Proveedor_Activo = 1');
        
        return Inertia::render('productos/index', [
            'products' => $productos,
            'proveedores' => $proveedores // Enviamos esto para la validación en Vue
        ]);
    }

    public function store(Request $request)
    {
        $accion = $request->id_prod ? 'actualizar' : 'agregar';

        DB::statement('EXEC sp_gestion_inventario 
            @accion = ?, 
            @id_prod = ?,
            @nombre = ?, 
            @desc = ?, 
            @p_compra = ?, 
            @p_venta = ?, 
            @cant = ?, 
            @num_lote = ?, 
            @caducidad = ?,
            @nombre_proveedor = ?', 
            [
                $accion,
                $request->id_prod,
                $request->nombre,
                $request->descripcion,
                $request->precio_compra,
                $request->precio_venta,
                $request->cantidad,
                $request->num_lote,
                $request->fecha_caducidad,
                $request->nombre_proveedor
            ]
        );

        return redirect()->back()->with('success', 'Operación exitosa');
    }

    public function destroy($id)
    {
        DB::statement('EXEC sp_gestion_inventario @accion = ?, @id_prod = ?', ['eliminar', $id]);
        return redirect()->back();
    }
}