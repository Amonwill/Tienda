<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productos_Cat extends Model
{
    use HasFactory;

    protected $table = 'Productos_Cat';
    protected $primaryKey = 'ID_Producto';
    public $timestamps = false; 

    protected $fillable = [
        'Nombre_Producto', 
        'Descripcion_Producto', 
        'ID_Prove', 
        'Precio_Compra', 
        'Precio_Venta', 
        'Cantidad', 
        'Lote', 
        'Producto_Activo'
    ];

    protected $casts = [
        'Precio_Compra' => 'float',
        'Precio_Venta' => 'float',
        'Producto_Activo' => 'boolean',
    ];
}