<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProveedorController extends Controller
{
    public function index()
    {
        // Traemos solo los activos para la tabla principal
        $proveedores = DB::table('Proveedores_Cat')
            ->where('Proveedor_Activo', 1)
            ->orderBy('ID_Proveedor', 'desc')
            ->get();

        return Inertia::render('proveedores/index', [
            'providers' => $proveedores
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:50',
            'descripcion' => 'required|max:100',
            'telefono' => 'required|max:15',
            'correo' => 'required|email|max:50',
            'direccion' => 'required|max:150',
            'ciudad' => 'required|max:50',
        ]);

        DB::table('Proveedores_Cat')->insert([
            'Nombre_Proveedor'      => $request->nombre,
            'Descripcion_Proveedor' => $request->descripcion,
            'Telefono_Proveedor'    => $request->telefono,
            'Correo_Proveedor'      => $request->correo,
            'Direccion_Completa'    => $request->direccion,
            'Ciudad_Estado'         => $request->ciudad,
            'Proveedor_Activo'      => 1
        ]);

        return redirect()->back()->with('message', 'Proveedor guardado.');
    }

    public function update(Request $request, $id)
    {
        DB::table('Proveedores_Cat')
            ->where('ID_Proveedor', $id)
            ->update([
                'Nombre_Proveedor'      => $request->nombre,
                'Descripcion_Proveedor' => $request->descripcion,
                'Telefono_Proveedor'    => $request->telefono,
                'Correo_Proveedor'      => $request->correo,
                'Direccion_Completa'    => $request->direccion,
                'Ciudad_Estado'         => $request->ciudad,
            ]);

        return redirect()->back();
    }

    public function destroy($id)
    {
        // Borrado lógico para no romper integridad de inventario
        DB::table('Proveedores_Cat')
            ->where('ID_Proveedor', $id)
            ->update(['Proveedor_Activo' => 0]);

        return redirect()->back();
    }
}