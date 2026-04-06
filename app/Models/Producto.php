<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    //
    protected $fillable = ['nombre'];

    public function pedidos(){

        return $this->hasmany(Pedido::class, 'producto_id');
        
    }
}
