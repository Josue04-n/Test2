<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    //

    protected $fillable = ['cedula', 'nombre', 'apellido'];

    //
    public function pedidos(){

        return $this->hasmany(Pedido::class, 'cliente_id');
        
    }
}
