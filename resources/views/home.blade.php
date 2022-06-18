@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <h1 class="mb-5">Bienvendio: {{ auth()->user()->name }}</h1>

            <div class="col-md-6 col-12">
                <div class="row">
                    <div class="col">
                        @include('buscador')
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

                    @if (isset($tracks) and count($tracks) )
                        
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
                                        <input type="hidden" name="id" value="{{ $track->id }}" />
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
                        
                    @endif
                    
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection
