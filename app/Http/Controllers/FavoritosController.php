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

    public function add(Request $req){
        
        $f = new Favorito;
        $f->user_id = (int)auth()->user()->id;
        $f->spotify_id = $req->spotify_id;
        $f->cancion = $req->cancion;
        $f->url = $req->url;
        $f->album = $req->album;
        $f->artista = $req->artista;
        $f->nota = $req->nota;
        $f->created_at = now();
        $f->updated_at = null;
        $f->save();

        return json_encode([
            'resp' => 1,
        ]);
    }
}
