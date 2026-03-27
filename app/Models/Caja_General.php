<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Caja_General extends Model
{
    protected $table = 'Caja_General';
    protected $primaryKey = 'ID_Session';
    public $timestamps = false;
    protected $fillable = [
        'ID_Caja_Fisica', 'Fecha_Apertura', 'Fecha_Cierre', 
        'Saldo_Inicial', 'Ingresos_Totales', 'Egresos_Totales', 'Estado_Caja'
    ];
    protected $casts = [
        'Saldo_Inicial' => 'float',
        'Ingresos_Totales' => 'float',
        'Egresos_Totales' => 'float',
        'Estado_Caja' => 'boolean',
        'Fecha_Apertura' => 'datetime',
        'Fecha_Cierre' => 'datetime'
    ];
}