<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulos extends Model
{
    protected $fillable = ['id_categoria', 'codigo', 'nombre', 'precio_venta', 'stock', 'descripcion', 'condicion']; // el modelo de la db

   /* public function categoria(){
        return $this->belongsTo('app\Categoria');

        DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dblaravel56
DB_USERNAME=root
DB_PASSWORD=
    }*/
}
