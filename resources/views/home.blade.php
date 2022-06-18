@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <h1 class="mb-5">Bienvendio: {{ auth()->user()->name }}</h1>

            <div class="col-md-6 col-12">
                <div class="row">
                    <div class="col">
                        @include('buscador', [
                            'busqueda' => isset($busqueda) ? $busqueda : null,
                        ])
                    </div>
                </div>
            </div>
            
            <table class="table">
                <tbody>

                    <thead>
                        <tr class="table-dark">
                            <th>#</th>
                            <th>Artísta</th>
                            <th>Album</th>
                            <th>Cancion</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    @php
                        $numItems = count($tracks);
                    @endphp

                    @if (isset($tracks) and $numItems )
                        
                        @foreach ($tracks as $i => $track)
                            
                            <tr>
                                <td>{{ $i + 1 + $offset }}</td>

                                <td>
                                    <a href="{{ $track->artists[0]->external_urls->spotify }}" target="_blank">
                                        {{ $track->artists[0]->name }}
                                    </a>
                                </td>

                                <td>
                                    <a href="{{ $track->album->external_urls->spotify }}" target="_blank">
                                        {{ $track->album->name}}
                                    </a>
                                    
                                </td>

                                <td>
                                    <a href="{{ $track->external_urls->spotify }}" target="_blank">
                                        {{ $track->name}}
                                    </a>

                                </td>

                                <td>
                                    
                                    <form action="" method="post">
                                        @csrf
                                        <input type="hidden" name="spotify_id" value="{{ $track->id }}" />
                                        <input type="hidden" name="cancion" value="{{ $track->name}}" />
                                        <input type="hidden" name="url" value="{{ $track->external_urls->spotify }}" />
                                        <input type="hidden" name="album" value="{{ $track->album->name}}" />
                                        <input type="hidden" name="artista" value="{{ $track->artists[0]->name }}" />

                                        <button type="submit" class="btn btn-info">Agregar a Favoritos</button>
                                    </form>
                                    
                                </td>
                            </tr>

                        @endforeach

                    @else
                        <tr>
                            <td colspan="5" class="text-center">No se econtraron canciones</td>
                        </tr>
                    @endif
                    
                </tbody>
                
            </table>
            <hr/>
            <p class="lead">Encontrados: {{ $numItems }} de {{$total}} </p>

        </div>
    </div>
</div>
@endsection
