<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Bandeja_Alertas extends Model
{
    protected $table = 'Bandeja_Alertas';
    protected $primaryKey = 'ID_Alerta';
    public $timestamps = false;
    protected $fillable = ['Mensaje', 'Tipo_Alerta', 'Fecha_Generada'];
}