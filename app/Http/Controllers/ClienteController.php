<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Models\Pedido;
class ClienteController extends Controller

{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Cliente::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        //
        return response()->json($cliente);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        //
    }

    public function buscarPorCedula($cedula){
        $cliente = Cliente::where('cedula',$cedula)->first();

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }
        
        return response()->json($cliente);

    }

    public function pedidosDelCliente($cedula){
        $cliente = Cliente::where('cedula',$cedula)
            ->with('pedidos.producto')
            ->first();

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        return response()->json([
            'cliente' => $cliente->apellido . ' ' . $cliente->nombre,
            'pedidos' => $cliente->pedidos,
        ]);


    }
}
