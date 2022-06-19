<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Favorito extends Model
{
    use HasFactory;

    protected $table = 'favoritos';

    public function getSerial(){
        return Crypt::encryptString($this->id);
    }
}
