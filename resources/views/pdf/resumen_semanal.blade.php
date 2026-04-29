<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; padding: 30px; color: #1e293b; }
        .header { border-bottom: 4px solid #1e293b; padding-bottom: 10px; margin-bottom: 30px; }
        .title { font-size: 24px; font-weight: 900; text-transform: uppercase; font-style: italic; }
        .card { background: #f1f5f9; padding: 20px; border-radius: 15px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; background: #1e293b; color: white; padding: 10px; font-size: 10px; text-transform: uppercase; }
        td { padding: 10px; border-bottom: 1px solid #e2e8f0; font-size: 12px; }
        .footer { margin-top: 50px; text-align: center; font-size: 9px; color: #94a3b8; }
        .highlight { color: #2563eb; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <span class="title">La Moderna - Resumen Semanal</span>
        <div style="float: right;">Semana {{ date('W') }} de {{ date('Y') }}</div>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Rendimiento por Día</h3>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Día</th>
                    <th>Operaciones</th>
                    <th style="text-align: right;">Total Vendido</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ventasSemanales as $v)
                <tr>
                    <td>{{ $v->fecha }}</td>
                    <td style="text-transform: capitalize;">{{ $v->dia }}</td>
                    <td>{{ $v->ops }}</td>
                    <td style="text-align: right;">${{ number_format($v->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Top Productos de la Semana</h3>
        <ul>
            @foreach($topProductosSemana as $p)
                <li><span class="highlight">{{ $p->Nombre_Producto }}</span>: {{ $p->cantidad }} unidades vendidas.</li>
            @endforeach
        </ul>
    </div>

    <div class="footer">
        Este reporte es para fines administrativos. Generado por el sistema integral La Moderna.
    </div>
</body>
</html>