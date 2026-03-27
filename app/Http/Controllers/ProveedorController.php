<?php

namespace App\Http\Controllers;

use App\Models\Proveedores_Cat;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProveedorController extends Controller
{
    public function index()
    {
        // Obtenemos todos los proveedores ordenados por nombre
        $proveedores = Proveedores_Cat::orderBy('Nombre_Proveedor', 'asc')->get();

        return Inertia::render('Providers/Index', [
            'providers' => $proveedores
        ]);
    }

    public function store(Request $request)
    {
        // Validación básica antes de insertar
        $request->validate([
            'Nombre_Proveedor' => 'required|string|max:50',
            'Telefono_Proveedor' => 'required|string|max:15',
            'Ciudad_Delegacion' => 'required|string|max:40',
        ]);

        // Creamos el registro en la base de datos
        Proveedores_Cat::create([
            'Nombre_Proveedor'      => $request->Nombre_Proveedor,
            'Descripcion_Proveedor' => $request->Descripcion_Proveedor,
            'Telefono_Proveedor'    => $request->Telefono_Proveedor,
            'Calle'                 => $request->Calle,
            'Numero_Calle'          => $request->Numero_Calle,
            'Codigo_Postal'         => $request->Codigo_Postal,
            'Ciudad_Delegacion'     => $request->Ciudad_Delegacion,
            'Proveedor_Activo'      => 1 // Por defecto al crearlo
        ]);

        return redirect()->back()->with('message', 'Proveedor registrado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $proveedor = Proveedores_Cat::findOrFail($id);
        $proveedor->update($request->all());

        return redirect()->back()->with('message', 'Proveedor actualizado.');
    }

    public function destroy($id)
    {
        // En lugar de borrar físicamente, cambiamos el estado (Baja lógica)
        $proveedor = Proveedores_Cat::findOrFail($id);
        $proveedor->update(['Proveedor_Activo' => 0]);

        return redirect()->back()->with('message', 'Proveedor desactivado.');
    }
}