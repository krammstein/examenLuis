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
                                        <input type="hidden" name="serial" value="{{ $item->getSerial() }}" />

                                        <button class="btn btn-danger btn-rem-fav" type="submit"><i class="fas fa-trash"></i></button>
                                    </form>

                                    <button class="btn btn-warning btn-edit-nota" type="button"><i class="far fa-edit"></i></button>
                                    
                                </td>

                                <td>
                                    <a href="{{ $item->url }}" target="_blank" rel="noopener noreferrer">
                                        {{ $item->cancion }}
                                    </a>
                                </td>

                                <td>{{ $item->artista }}</td>
                                <td>{{ $item->album }}</td>

                                <td class="nota-col">

                                    <p class="nota-text">{{ $item->nota }}</p>

                                    <form action="" method="post" class="nota-form d-none">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="serial" value="{{ $item->getSerial() }}" />

                                        <textarea name="nota" rows="3" class="form-control nota-input">{{ $item->nota }}</textarea>

                                        <button class="btn btn-primary btn-save-nota" type="button" disabled>Guardar</button>
                                        <button type="reset" class="btn btn-secondary btn-cancel-nota">Cancelar</button>
                                        
                                    </form>
                                </td>

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

    <script>

        class FavoritosTable{

            constructor(){

                this.btnsEdit = document.querySelectorAll('.btn-edit-nota');
                this.btnsCancel = document.querySelectorAll('.btn-cancel-nota');
                this.btnsRemFav = document.querySelectorAll('.btn-rem-fav');

                this.init();
            }

            init(){

                this.btnsEdit.forEach((btn) => {

                    let tr = btn.parentNode.parentNode;
                    
                    btn.addEventListener('click', () => {

                        tr.classList.add('table-primary');

                        let col = tr.querySelector('.nota-col');

                        let obj = {
                            notaForm: col.querySelector('.nota-form'),
                            notaText: col.querySelector('.nota-text'),
                            notaInput: col.querySelector('.nota-input'),
                            btnSave: col.querySelector('.btn-save-nota'),
                        };
                        
                        obj.notaForm.classList.remove('d-none');
                        obj.notaText.classList.add('d-none');
                        obj.btnSave.disabled = false;
                        obj.notaInput.focus();

                        obj.btnSave.addEventListener('click', () => {
                            this.save(obj, tr);
                        });

                    });

                });

                this.btnsCancel.forEach((btn) => {

                    let tr = btn.parentNode.parentNode.parentNode;

                    btn.addEventListener('click', () => {

                        tr.classList.remove('table-primary');

                        let col = tr.querySelector('.nota-col');
                        
                        let notaForm = col.querySelector('.nota-form');
                        let notaText = col.querySelector('.nota-text');
                        let btnSave = col.querySelector('.btn-save-nota');
                        
                        notaForm.classList.add('d-none');
                        notaText.classList.remove('d-none');
                        btnSave.disabled = true;
                        
                    });

                });

                this.btnsRemFav.forEach((btn) => {

                    btn.addEventListener('click', (e) => {
                        e.preventDefault();

                        Swal.fire({
                            title: 'Realmente quieres eliminar esta canción?',
                            showCancelButton: true,
                            confirmButtonText: 'Borrar',
                            cancelButtonText: 'Cancelar',

                        }).then((result) => {
                        
                            if (result.isConfirmed) {

                                this.remove(btn);

                            }

                        });
                    });
                });

            }

            async save(obj, tr){

                obj.btnSave.innerHTML = '<i class="fas fa-cog fa-spin fa-lg"></i>';
                obj.btnSave.disabled = true;

                let data = new FormData(obj.notaForm);
                
                let resp = await fetch('{{ route('favoritos.modify') }}', {
                    method: "POST",
                    body: data,
                });

                let res = await resp.json();

                obj.btnSave.innerHTML = 'Guardar';
                obj.btnSave.disabled = false;

                if(res.resp == 1){

                    obj.notaForm.classList.add('d-none');
                    obj.notaText.classList.remove('d-none');
                    obj.notaText.innerHTML = obj.notaInput.value;
                    tr.classList.remove('table-primary');
                    
                }else{

                    Swal.fire(
                        'Ocurrieron errores',
                        res.err.toString(),
                        'error'
                    );
                }
            }

            async remove(btn){

                let icon = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-cog fa-spin fa-lg"></i>';
                btn.disabled = true;

                let form = btn.parentNode;
                let tr = form.parentNode.parentNode;
                tr.classList.add('table-primary');

                let resp = await fetch('{{ route('favoritos.remove') }}', {
                    method: "POST",
                    body: new FormData(form),
                });

                let res = await resp.json();

                btn.innerHTML = icon;
                btn.disabled = false;

                if(res.resp == 1){

                    tr.remove();

                }else{

                    Swal.fire(
                        'Ocurrieron errores',
                        res.err.toString(),
                        'error'
                    );
                }
            }
        }

        const favtab = new FavoritosTable;

    </script>
    
@endsection