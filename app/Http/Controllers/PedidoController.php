<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Producto;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cedula' => ['required', 'string'],
            'nombre_producto' => ['nullable', 'string'],
            'producto' => ['nullable', 'string'],
        ]);

        $cliente = Cliente::where('cedula', $request->cedula)->first();
        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        $nombreProducto = $request->nombre_producto ?? $request->producto;

        $producto = Producto::where('nombre', $nombreProducto)->first();
        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }


        $pedido = Pedido::create([
            'cliente_id' => $cliente->id,
            'producto_id' => $producto->id
        ]);

        $pedido->load('cliente','producto');

        return response()->json($pedido, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Pedido $pedido)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pedido $pedido)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pedido $pedido)
    {
        //
    }
}
