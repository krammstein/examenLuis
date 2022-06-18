<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
/**
 * Usamos la libreria que instalamos con composer para hacer llamadas a cualquier API
 */
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Controla la visa home
 */
class HomeController extends Controller
{

    private $token;
    private $offset;
    private $total;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //todas las acciones en este controlador necesitan autenticacion de usaurio - pass
        $this->middleware('auth');
    }

    /**
     * Muestra la vista home - listado de canciones aleatorio
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $artists = config('app.spotify_init_artists');

        $tracks = $this->getTracks(config('app.spotify_limit'), 0, $artists[rand(0, count($artists) - 1)] );

        return view('home', [
            'tracks' => $tracks,
            'total' => $this->total,
            'offset' => $this->offset,
        ]);
    }

    public function buscar(Request $req) {

        $tracks = $this->getTracks(
            config('app.spotify_limit'), 
            (int)$req->offset, 
            (string)$req->busqueda);

        return view('home', [
            'tracks' => $tracks,
            'total' => $this->total,
            'offset' => $this->offset,
            'busqueda' => (string)$req->busqueda,
        ]);
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

    /**
     * Obtiene listado de canciones
     *
     * @return array
     */
    private function getTracks($limit = 15, $offset = 0, $query = 'Rammstein'){
        
        try {

            $this->initToken();

            $client = new Client;

            $resp = $client->request('GET', 
                config('app.spotify_uri_search') . 
                    '?q='.urlencode($query).'&type=track%2Cartist&limit='. (int)$limit. '&offset='. (int)$offset, 
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Authorization' => "Bearer " . $this->token,
                    ],
                ]
            );

            if ($resp->getStatusCode() == 200) {

                $body = $resp->getBody();

                $json = json_decode($body);

                //print_r($json->tracks);

                //foreach($json->tracks->items as $key => $val){
                    
                    /* foreach($val as $attr => $v){
                        echo "<br/> $attr <br/>";
                        print_r($val->{$attr});
                        echo "<br/>";
                    } */

                    //echo "<br/> Artist => ".$val->artists[0]->name. " Album => ". $val->album->name. " song => ". $val->name . " href => ". $val->external_urls->spotify;
                //}

                $this->total = $json->tracks->total;

                $this->offset = $json->tracks->offset;

                return $json->tracks->items;

            } 

        } catch (ClientException $th) {

            echo $th->getMessage();

        }
        
    }
}
