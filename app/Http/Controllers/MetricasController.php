<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class MetricasController extends Controller
{
    public function index()
    {
        // Usamos la fecha actual de SQL Server directamente para evitar desajustes con PHP
        $resumen = DB::select("
            SELECT 
                ISNULL(SUM(Total), 0) AS ingresos,
                COUNT(ID_Venta) AS operaciones
            FROM Ventas 
            WHERE CAST(Fecha_y_Hora_Venta AS DATE) = CAST(GETDATE() AS DATE)")[0];

        // Para los productos más vendidos del día (Hoy):
        // Cambiamos Detalle_Ventas -> Detalle_Venta
        $topProductosHoy = DB::select("
            SELECT TOP 5 
                p.Nombre_Producto, 
                SUM(dv.Cantidad) as total 
            FROM Detalle_Venta dv 
            JOIN Productos_Cat p ON dv.ID_Producto = p.ID_Producto
            JOIN Ventas v ON dv.ID_Venta = v.ID_Venta
            WHERE CAST(v.Fecha_y_Hora_Venta AS DATE) = CAST(GETDATE() AS DATE)
            GROUP BY p.Nombre_Producto 
            ORDER BY total DESC");

        return Inertia::render('Metricas/index', [
            'stats' => [
                'ingresos' => (float)$resumen->ingresos,
                'operaciones' => (int)$resumen->operaciones,
                'promedio' => $resumen->operaciones > 0 ? (float)($resumen->ingresos / $resumen->operaciones) : 0
            ],
            'topProductos' => $topProductosHoy
        ]);
    }

    public function descargarResumenSemanal()
    {
        $ventasSemanales = DB::select("
            SELECT 
                CAST(Fecha_y_Hora_Venta AS DATE) AS fecha,
                FORMAT(Fecha_y_Hora_Venta, 'dddd', 'es-MX') AS dia,
                SUM(Total) AS total,
                COUNT(ID_Venta) AS ops
            FROM Ventas
            WHERE Fecha_y_Hora_Venta >= DATEADD(DAY, -7, GETDATE())
            GROUP BY CAST(Fecha_y_Hora_Venta AS DATE), FORMAT(Fecha_y_Hora_Venta, 'dddd', 'es-MX')
            ORDER BY fecha ASC
        ");

        $topProductosSemana = DB::select("
            SELECT TOP 5 p.Nombre_Producto, SUM(dv.Cantidad) as cantidad
            FROM Detalle_Venta dv 
            JOIN Productos_Cat p ON dv.ID_Producto = p.ID_Producto
            JOIN Ventas v ON dv.ID_Venta = v.ID_Venta
            WHERE v.Fecha_y_Hora_Venta >= DATEADD(DAY, -7, GETDATE())
            GROUP BY p.Nombre_Producto ORDER BY cantidad DESC
        ");

        $pdf = Pdf::loadView('pdf.resumen_semanal', compact('ventasSemanales', 'topProductosSemana'));
        
        return $pdf->download('Resumen_Semanal_LaModerna_'.date('W-Y').'.pdf');
    }
}