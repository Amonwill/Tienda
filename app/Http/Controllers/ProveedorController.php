<?php
namespace App\Http\Controllers;
use App\Models\Proveedores_Cat;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedores_Cat::orderBy('Nombre_Proveedor', 'asc')->get();

        return Inertia::render('Providers/Index', [
            'providers' => $proveedores
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'Nombre_Proveedor' => 'required|string|max:50',
            'Telefono_Proveedor' => 'required|string|max:15',
            'Ciudad_Delegacion' => 'required|string|max:40',
        ]);
        Proveedores_Cat::create([
            'Nombre_Proveedor'      => $request->Nombre_Proveedor,
            'Descripcion_Proveedor' => $request->Descripcion_Proveedor,
            'Telefono_Proveedor'    => $request->Telefono_Proveedor,
            'Calle'                 => $request->Calle,
            'Numero_Calle'          => $request->Numero_Calle,
            'Codigo_Postal'         => $request->Codigo_Postal,
            'Ciudad_Delegacion'     => $request->Ciudad_Delegacion,
            'Proveedor_Activo'      => 1 
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
        $proveedor = Proveedores_Cat::findOrFail($id);
        $proveedor->update(['Proveedor_Activo' => 0]);
        return redirect()->back()->with('message', 'Proveedor desactivado.');
    }
}