<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente;
use App\Models\Producto;

class Pedido extends Model
{
    //
    protected $fillable= [
        'cliente_id',
        'producto_id'
    ];

    public function cliente() {

        return $this->belongsTo(Cliente::class, 'cliente_id');

    }

    public function producto() {

        return $this->belongsTo(Producto::class, 'producto_id');
         
    }
}
