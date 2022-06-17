<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
/**
 * Usamos la libreria que instalamos con composer para hacer llamadas a cualquier API
 */
use GuzzleHttp\Client;

/**
 * Controla la visa home
 */
class HomeController extends Controller
{

    private $token;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //todas las acciones en este controlador necesitan autenticacion de usaurio - pass
        $this->middleware('auth');

        $this->initToken();
    }

    /**
     * Muestra la vista home - listado de canciones aleatorio
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Inicializa el attr token de Spotify
     *
     * @return void
     */
    private function initToken(){

        $client = new Client;
        $cred = base64_encode(env('SPOTIFY_ID_CLIENT') . ':' . env('SPOTIFY_SECRET'));

        $resp = $client->request('POST', config('app.spotify_uri_token'), [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => "Basic $cred",
            ],
            'form_params' => [
                'grant_type' => 'client_credentials',
            ],
        ]);

        if($resp->getStatusCode() == 200 ){
            
            $body = $resp->getBody();

            $json = json_decode($body);

            $this->token = $json->access_token;

        }else{

            throw new Exception("No se pudo obtener el token");
        }

    }
}
