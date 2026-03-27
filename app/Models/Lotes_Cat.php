<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Lotes_Cat extends Model
{
    protected $table = 'Lotes_Cat';
    protected $primaryKey = 'ID_Lote';
    public $timestamps = false;
    protected $fillable = ['Num_Lote', 'Fecha_Caducidad'];
    protected $casts = [
        'Fecha_Caducidad' => 'date',
    ];
}