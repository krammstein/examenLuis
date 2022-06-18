@extends('layouts.app')

@section('content')
@php
    $idTable = 't-'. rand(0,9). rand(0,9). rand(0,9). rand(0,9);
@endphp

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
            
            <table class="table" id="{{$idTable}}" style="background: #f2f2f2;">
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
                                    
                                    <form action="" method="post" class="form-add-fav">
                                        @csrf
                                        <input type="hidden" name="spotify_id" value="{{ $track->id }}" />
                                        <input type="hidden" name="cancion" value="{{ $track->name}}" />
                                        <input type="hidden" name="url" value="{{ $track->external_urls->spotify }}" />
                                        <input type="hidden" name="album" value="{{ $track->album->name}}" />
                                        <input type="hidden" name="artista" value="{{ $track->artists[0]->name }}" />

                                        <textarea name="nota" placeholder="Comentarios..." class="form-control d-none f-note" rows="3" autofocus required></textarea>

                                        <button type="button" class="btn btn-success btn-add-fav mt-2"><i class="fas fa-heart"></i></button>

                                        <button type="submit" class="btn btn-primary btn-save-fav mt-2 d-none" disabled>Guardar</button>

                                        <button type="button" class="btn btn-secondary btn-cancel-fav d-none mt-2">Cancelar</button>

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

<script>

    class Add2Fav{

        constructor(){
            this.table = document.querySelector('#{{$idTable}}');
            this.forms = this.table.querySelectorAll('.form-add-fav');
            
            this.init();
        }

        init(){

            this.forms.forEach((form) => {

                let btn = form.querySelector('.btn-add-fav');
                let btnCancel = form.querySelector('.btn-cancel-fav');
                let tr = form.parentNode.parentNode;

                btn.addEventListener('click', () => {
                    this.showTextArea(btn, form, btnCancel, tr);
                });

                btnCancel.addEventListener('click', () => {
                    this.hideTextArea(btnCancel, form, btn, tr);
                });
                
            });
        }

        showTextArea(btn, form, btnCancel, tr){
            let btnSave = form.querySelector('.btn-save-fav');
            let note = form.querySelector('.f-note');
            form.querySelector('.btn-cancel-fav').classList.remove('d-none');
            note.classList.remove('d-none');
            btnSave.classList.remove('d-none');
            btnSave.disabled = false;
            btn.classList.add('d-none');
            btn.disabled = true;
            btnCancel.classList.remove('d-none');
            note.focus();
            tr.classList.add('table-primary');
        }

        hideTextArea(btnCancel, form, btn, tr){
            let btnSave = form.querySelector('.btn-save-fav');
            let note = form.querySelector('.f-note');
            btnCancel.classList.add('d-none');
            btnSave.classList.add('d-none');
            btnSave.disabled = true;
            note.classList.add('d-none');
            note.value = '';
            btn.disabled = false;
            btn.classList.remove('d-none');
            tr.classList.remove('table-primary');
        }

    }

    const add = new Add2Fav;

</script>
@endsection
