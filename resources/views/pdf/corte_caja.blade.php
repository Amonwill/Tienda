<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Corte de Caja Diario - La Moderna</title>
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
        .resumen-grid { display: block; margin-bottom: 10px; }
        .resumen-item { display: inline-block; width: 30%; font-size: 11px; }
        .total-box { margin: 20px; padding: 20px; background-color: #2d3748; color: white; border-radius: 10px; text-align: right; }
        .total-box h2 { margin: 0; font-size: 18px; }
        .footer { position: fixed; bottom: 20px; width: 100%; text-align: center; font-size: 9px; color: #a0aec0; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>La Moderna</h1>
        <p>Reporte de Corte de Caja Diario | Fecha: {{ date('d/m/Y') }}</p>
    </div>

    @php 
        $granTotalVendido = 0;
        $granSaldoInicial = 0;
    @endphp

    @foreach($sesiones as $sesion)
        <div class="section">
            <div class="section-title">
                {{ $sesion->Nombre_Caja }} (Sesión #{{ $sesion->ID_Session }})
                <span style="float: right; font-size: 9px;">Estatus: {{ $sesion->Estado_Caja ? 'ABIERTA' : 'CERRADA' }}</span>
            </div>
            
            <div class="resumen-grid">
                <div class="resumen-item"><strong>Saldo Inicial:</strong> ${{ number_format($sesion->Saldo_Inicial, 2) }}</div>
                <div class="resumen-item"><strong>Ingresos:</strong> ${{ number_format($sesion->Ingresos_Totales, 2) }}</div>
                <div class="resumen-item"><strong>Egresos:</strong> ${{ number_format($sesion->Egresos_Totales, 2) }}</div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th class="text-right">Cantidad</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sesion->productos as $prod)
                        <tr>
                            <td>{{ $prod->Nombre_Producto }}</td>
                            <td class="text-right">{{ $prod->total_cant }} pzas</td>
                            <td class="text-right">${{ number_format($prod->subtotal_prod, 2) }}</td>
                        </tr>
                    @endforeach
                    @if(count($sesion->productos) == 0)
                        <tr><td colspan="3" style="text-align: center; font-style: italic;">No hay ventas registradas en esta sesión.</td></tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-right bold">Total Caja:</td>
                        <td class="text-right bold">${{ number_format($sesion->Ingresos_Totales, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @php 
            $granTotalVendido += $sesion->Ingresos_Totales;
            $granSaldoInicial += $sesion->Saldo_Inicial;
        @endphp
    @endforeach

    <div class="total-box">
        <p style="margin: 0; font-size: 10px; opacity: 0.8;">CONSOLIDADO GLOBAL DEL DÍA</p>
        <h2>TOTAL VENDIDO: ${{ number_format($granTotalVendido, 2) }}</h2>
        <p style="margin: 5px 0 0;">Fondo Total en Cajas (Saldos Iniciales): ${{ number_format($granSaldoInicial, 2) }}</p>
        <p style="margin: 5px 0 0; font-weight: bold; font-size: 14px;">EFECTIVO TEÓRICO TOTAL: ${{ number_format($granTotalVendido + $granSaldoInicial, 2) }}</p>
    </div>

    <div class="footer">
        Generado por Sistema La Moderna - Módulo de Auditoría de Caja
    </div>
</body>
</html>