<form action="" method="post" class="row row-cols-lg-auto g-3 align-items-center mb-4">
    @csrf

    <div class="col-12">
        <input type="text" placeholder="Buscar artísta o canción" class="form-control" name="query" required />
    </div>
    
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Buscar</button>
    </div>
    
</form>