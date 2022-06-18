<?php

namespace App\Http\Controllers;

use App\Models\Favorito;
use Illuminate\Http\Request;

class FavoritosController extends Controller
{

    public function __construct()
    {
        //todas las acciones en este controlador necesitan autenticacion de usaurio - pass
        $this->middleware('auth');
    }
    
    public function index(){

        //$items = Favorito::where('user_id', auth()->user()->id)->get();
        $items = Favorito::where('user_id', auth()->user()->id)->paginate(config('app.pagination'));

        return view('favoritos.index', [
            'items' => $items,
        ]);
    }

    public function create(Request $req){

    }
}
