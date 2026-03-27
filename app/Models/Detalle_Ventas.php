<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Detalle_Ventas extends Model
{
    protected $table = 'Detalle_Venta';
    protected $primaryKey = 'ID_Detalle_Venta';
    public $timestamps = false;
    protected $fillable = [
        'ID_Venta', 'ID_Producto', 'Cantidad', 'Precio_Unitario'
    ];
    protected $casts = [
        'Precio_Unitario' => 'float',
    ];
}