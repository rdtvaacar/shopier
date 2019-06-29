<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class U_kat_isim extends Model
{
    protected $connection = 'mysql2';
    function kategori_isimleri()
    {
    return $this->belongsToMany(U_kat::class);
    }
}
