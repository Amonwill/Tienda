<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    protected $table = 'Ventas';
    protected $primaryKey = 'ID_Venta';
    public $timestamps = false; 
    protected $fillable = [
        'ID_Cliente', 'ID_Caja', 'Fecha_y_Hora_Venta', 
        'Total', 'Pago_Con', 'Cambio'
    ];
    protected $casts = [
        'Total' => 'float',
        'Pago_Con' => 'float',
        'Cambio' => 'float',
        'Fecha_y_Hora_Venta' => 'datetime'
    ];
}