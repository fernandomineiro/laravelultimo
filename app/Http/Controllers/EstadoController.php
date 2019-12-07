<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estado;

class EstadoController extends Controller
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

        $uf = $request->input('uf');

        $uf = Estado::where(['estado' => $uf])->first();
        return response()->json([
            'uf' => $uf
        ]);
    }

    public function buscarPorPais($id)
    {

        return response()->json([
            'estados' => Estado::where(['idpais' => $id])->orderBy('estado')->get()
        ]);
    }
}
