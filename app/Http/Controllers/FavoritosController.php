<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\Favorito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

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
        
        $err = '';
        $resp = 0;

        $validator = Validator::make($req->all(), [
            'nota' => 'required|max:250|min:10',
        ]);
        
        if(!$validator->fails()){

            if (Favorito::where('spotify_id', $req->spotify_id)->count() == 0) {

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
                $resp = 1;

            }else{
                $err = ['La canción ya se encuentra en los Favoritos'];
            }

        }else{

            $err = $validator->errors()->all();

        }

        return json_encode([
            'resp' => $resp,
            'err' => $err,
        ]);
    }

    public function remove(Request $req){

    }

    public function modify(Request $req){
        $resp = 0;
        $err = [];

        $id = (int)Crypt::decryptString($req->serial);

        if($id > 0){

            $validator = Validator::make($req->all(), [
                'nota' => 'required|max:250|min:10',
            ]);

            if(!$validator->fails()){

                $item = Favorito::where('id', $id)->first();

                if(isset($item->id)){

                    $item->nota = $req->nota;
                    $item->save();
                    $resp = 1;
                    
                }else{

                    $err = ['Esta canción no existe'];
                }

            }else{

                $err = $validator->errors()->all();
            }

        }else{
            $err = ['Esta canción no es válida'];
        }

        return json_encode([
            'resp' => $resp,
            'err' => $err,
        ]);
    }

}
