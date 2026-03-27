<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clientes_Cat extends Model
{
    protected $table = 'Clientes_Cat';
    protected $primaryKey = 'ID_Cliente';
    public $timestamps = false;
    protected $fillable = [
        'Nombre', 'Apellido_Paterno', 'Apellido_Materno', 
        'Numero_Telefono', 'Cliente_Activo'
    ];
    protected $casts = [
        'Cliente_Activo' => 'boolean',
    ];
}