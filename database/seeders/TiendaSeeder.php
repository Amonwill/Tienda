<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiendaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Un Cliente General
        DB::table('Clientes_Cat')->insert([
            'Nombre' => 'Publico', 'Apellido_Paterno' => 'General', 'Apellido_Materno' => 'X',
            'Numero_Telefono' => '0000000000', 'Cliente_Activo' => 1
        ]);

        // 2. Un Proveedor
        // 2. Un Proveedor (Ajustado a la versión simplificada)
        // 2. Un Proveedor (Agregamos Correo_Proveedor que es obligatorio)
        $idProv = DB::table('Proveedores_Cat')->insertGetId([
            'Nombre_Proveedor' => 'Distribuidora Global', 
            'Descripcion_Proveedor' => 'Bebidas y Alimentos',
            'Telefono_Proveedor' => '5512345678', 
            'Correo_Proveedor' => 'contacto@global.com', // <--- Faltaba este campo
            'Direccion_Completa' => 'Av. Central 100, Col. Centro, CP 55000', 
            'Ciudad_Estado' => 'CDMX', 
            'Proveedor_Activo' => 1
        ]);

        // 3. Un Lote
        $idLote = DB::table('Lotes_Cat')->insertGetId([
            'Num_Lote' => 'LOTE-2026-A', 'Fecha_Caducidad' => '2027-01-01'
        ]);

        // 4. Una Caja Física
        DB::table('Cajas_Cat')->insert(['Nombre_Caja' => 'Caja Principal', 'Ubicacion' => 'Mostrador', 'Activa' => 1]);

        // 5. Productos de prueba (¡Aquí está tu Coca!)
        DB::table('Productos_Cat')->insert([
            [
                'Nombre_Producto' => 'Coca Cola 600ml', 'Descripcion_Producto' => 'Refresco de cola',
                'ID_Prove' => $idProv, 'Precio_Compra' => 12.50, 'Precio_Venta' => 18.00,
                'Cantidad' => 50, 'Lote' => $idLote, 'Producto_Activo' => 1
            ],
            [
                'Nombre_Producto' => 'Sabritas Sal 45g', 'Descripcion_Producto' => 'Papas con sal',
                'ID_Prove' => $idProv, 'Precio_Compra' => 10.00, 'Precio_Venta' => 17.50,
                'Cantidad' => 30, 'Lote' => $idLote, 'Producto_Activo' => 1
            ]
        ]);
    }
}