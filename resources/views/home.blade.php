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

                let obj = {
                    btn: form.querySelector('.btn-add-fav'),
                    btnCancel: form.querySelector('.btn-cancel-fav'),
                    btnSave: form.querySelector('.btn-save-fav'),
                    tr: form.parentNode.parentNode,
                    note: form.querySelector('.f-note'),
                };
                
                obj.btn.addEventListener('click', () => {
                    this.showTextArea(obj);
                });

                obj.btnCancel.addEventListener('click', () => {
                    this.hideTextArea(obj);
                });

                obj.btnSave.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.save(form, obj);
                });
                
            });
        }

        showTextArea(obj){
            
            obj.btnCancel.classList.remove('d-none');
            obj.note.classList.remove('d-none');
            obj.btnSave.classList.remove('d-none');
            obj.btnSave.disabled = false;
            obj.btn.classList.add('d-none');
            obj.btn.disabled = true;
            obj.btnCancel.classList.remove('d-none');
            obj.note.focus();
            obj.tr.classList.add('table-primary');
        }

        hideTextArea(obj){
            
            obj.btnCancel.classList.add('d-none');
            obj.btnSave.classList.add('d-none');
            obj.btnSave.disabled = true;
            obj.note.classList.add('d-none');
            obj.note.value = '';
            obj.btn.disabled = false;
            obj.btn.classList.remove('d-none');
            obj.tr.classList.remove('table-primary');
        }

        async save(form, obj){

            obj.btnSave.innerHTML = '<i class="fas fa-cog fa-spin fa-lg"></i>';
            obj.btnSave.disabled = true;

            let data = new FormData(form);

            let resp = await fetch(
                '{{ route('favoritos.add') }}', {
                method: 'POST',
                body: data,
            });

            let res = await resp.json();

            obj.btnSave.innerHTML = 'Guardar';
            obj.btnSave.disabled = false;

            if(res.resp == 1){
                form.innerHTML = '<div><i class="fas fa-check"></i> Agregado</div>';
                obj.tr.classList.remove('table-primary');

            }else{
                alert(res.error);
            }
        }

    }

    const add2fav = new Add2Fav;

</script>
@endsection
