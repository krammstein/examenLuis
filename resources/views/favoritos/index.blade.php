@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row div col-12">
        
            <h1>Mis canciones Favoritas</h1>
            <p>Administra tus canciones favoritas</p>

            <table class="table">

                <thead>
                    <tr class="table-dark">
                        <th>Acciones</th>
                        <th>Cancion</th>
                        <th>Artísta</th>
                        <th>Album</th>
                    </tr>
                </thead>

                <tbody>
                    @if (count($items))
                        
                    @else
                        <tr>
                            <td colspan="4" class="text-center">No hay favoritos aún !</td>
                        </tr>
                    @endif                    
                </tbody>

            </table>
        </div>
    </div>

    
@endsection