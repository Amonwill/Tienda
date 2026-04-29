<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Proveedores_Cat extends Model
{
    protected $table = 'Proveedores_Cat';
    protected $primaryKey = 'ID_Proveedor';
    public $timestamps = false;
    protected $fillable = [
    'Nombre_Proveedor', 
    'Descripcion_Proveedor', 
    'Telefono_Proveedor', 
    'Correo_Proveedor', 
    'Direccion_Completa', 
    'Ciudad_Estado', 
    'Proveedor_Activo'
];
    protected $casts = [
        'Proveedor_Activo' => 'boolean',
    ];
}