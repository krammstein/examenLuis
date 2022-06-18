@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row div col-12">
        
            <h1>Mis canciones Favoritas</h1>
            <p>Administra tus canciones favoritas</p>

            <table class="table" style="background: #f2f2f2;">

                <thead>
                    <tr class="table-dark">
                        <th>Acciones</th>
                        <th>Cancion</th>
                        <th>Artísta</th>
                        <th>Album</th>
                        <th>Nota</th>
                    </tr>
                </thead>

                <tbody>
                    @if (count($items) > 0)

                    @foreach ($items as $item)
                        <tr>
                            <td class="d-flex justify-content-start">

                                <form action="{{ route('favoritos.remove') }}" method="post" class="me-1">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-danger" type="submit"><i class="fas fa-trash"></i></button>
                                </form>

                                <button class="btn btn-warning" type="button"><i class="far fa-edit"></i></button>
                                
                            </td>

                            <td>
                                <a href="{{ $item->url }}" target="_blank" rel="noopener noreferrer">
                                    {{ $item->cancion }}
                                </a>
                            </td>

                            <td>{{ $item->artista }}</td>
                            <td>{{ $item->album }}</td>
                            <td>{{ $item->nota }}</td>
                        </tr>
                    @endforeach
                        
                    @else
                        <tr>
                            <td colspan="5" class="text-center">No hay favoritos aún !</td>
                        </tr>
                    @endif                    
                </tbody>

            </table>

            {{$items->links()}}
            
        </div>
    </div>

    
@endsection