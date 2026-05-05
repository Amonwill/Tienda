<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen Semanal - La Moderna</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; margin: 0; padding: 0; font-size: 12px; }

        .header { background-color: #1a202c; color: white; padding: 20px; text-align: center; }
        .header h1 { margin: 0; font-style: italic; text-transform: uppercase; letter-spacing: 2px; }
        .header p { margin: 5px 0 0; font-size: 10px; opacity: 0.8; }

        .section { margin: 20px; padding: 15px; border: 1px solid #e2e8f0; border-radius: 10px; }
        .section-title { background-color: #f7fafc; padding: 8px; font-weight: bold; text-transform: uppercase; border-bottom: 2px solid #2d3748; margin-bottom: 10px; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #edf2f7; text-align: left; padding: 8px; border-bottom: 1px solid #cbd5e0; text-transform: uppercase; font-size: 10px; }
        td { padding: 8px; border-bottom: 1px solid #f7fafc; }

        .total-box { margin: 20px; padding: 20px; background-color: #2d3748; color: white; border-radius: 10px; text-align: right; }
        .total-box h2 { margin: 0; font-size: 18px; }

        .footer { position: fixed; bottom: 20px; width: 100%; text-align: center; font-size: 9px; color: #a0aec0; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>La Moderna</h1>
        <p>Reporte de Resumen Semanal | Semana {{ date('W') }} de {{ date('Y') }}</p>
    </div>

    @php 
        $granTotalVendido = 0; 
        $totalOperaciones = 0;
    @endphp

    <div class="section">
        <div class="section-title">
            RENDIMIENTO DIARIO
            <span style="float: right; font-size: 9px;">ESTATUS: COMPLETADO</span>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Día</th>
                    <th class="text-center">Operaciones</th>
                    <th class="text-right">Total Vendido</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ventasSemanales as $v)
                    @php 
                        $granTotalVendido += $v->total; 
                        $totalOperaciones += $v->ops;
                    @endphp
                    <tr>
                        <td>{{ $v->fecha }}</td>
                        <td style="text-transform: capitalize;">{{ $v->dia }}</td>
                        <td class="text-center">{{ $v->ops }}</td>
                        <td class="text-right">${{ number_format($v->total, 2) }}</td>
                    </tr>
                @endforeach
                @if(count($ventasSemanales) == 0)
                    <tr><td colspan="4" class="text-center" style="font-style: italic;">No hay ventas registradas en esta semana.</td></tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right bold">Total Semana:</td>
                    <td class="text-right bold">${{ number_format($granTotalVendido, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="section">
        <div class="section-title">TOP PRODUCTOS DE LA SEMANA</div>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th class="text-right">Cantidad Vendida</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topProductosSemana as $p)
                    <tr>
                        <td>{{ $p->Nombre_Producto }}</td>
                        <td class="text-right">{{ $p->cantidad }} pzas</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="total-box">
        <p style="margin: 0; font-size: 10px; opacity: 0.8;">CONSOLIDADO GLOBAL DE LA SEMANA</p>
        <h2>TOTAL VENDIDO: ${{ number_format($granTotalVendido, 2) }}</h2>
        <p style="margin: 5px 0 0;">Total de Operaciones Atendidas: {{ $totalOperaciones }}</p>
    </div>

    <div class="footer">
        Generado por Sistema La Moderna - Módulo de Reportes Semanales
    </div>
</body>
</html>