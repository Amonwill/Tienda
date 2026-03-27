<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cajas_Cat extends Model
{
    protected $table = 'Cajas_Cat';
    protected $primaryKey = 'ID_Caja_Fisica';
    public $timestamps = false;
    protected $fillable = ['Nombre_Caja', 'Ubicacion', 'Activa'];
    protected $casts = [
        'Activa' => 'boolean',
    ];
}