<?php

namespace App\Http\Controllers;

use App\Models\ComunicacaoTipo;
use Illuminate\Http\Request;

class ComunicacaoTipoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function buscar(Request $request)
    {
        $comunicacao_tipos = ComunicacaoTipo::all();
        return response()->json([
            'comunicacao_tipos' => $comunicacao_tipos
        ]);
    }
}
