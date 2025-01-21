<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $fillable = [
        'nombre',
        'apellido',
        'cedula',
        'telefono',
        'email',
        'direccion',
        'estado'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function scopeActivos($query)
    {
        return $query->where('estado', 1);
    }
}
