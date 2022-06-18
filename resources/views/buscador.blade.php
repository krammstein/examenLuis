<form action="{{ route('buscar') }}" method="post" class="row row-cols-lg-auto g-3 align-items-center mb-4">
    @csrf

    <input type="hidden" name="offset"  value="0" />

    <div class="col-12">
        <input type="text" placeholder="Buscar artísta o canción" class="form-control" name="busqueda" value="{{ $busqueda }}" required />
    </div>
    
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Buscar</button>
    </div>
    
</form>