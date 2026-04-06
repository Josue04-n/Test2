<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PedidoController;

//CLIENTESS
Route::get('clientes',                   [ClienteController::class, 'index']);
// Route::get('cliente/{cliente}',         [ClienteController::class, 'show']);
Route::get('clientes/buscar/{cedula}',     [ClienteController::class, 'buscarPorCedula']);
Route::get('clientes/{cedula}/pedidos',  [ClienteController::class, 'pedidosDelCliente']);    

//PRODUCTOS
Route::get('productos',[ProductoController::class, 'index']);

//PEDIDOS
Route::post('pedidos',[PedidoController::class, 'store']);


