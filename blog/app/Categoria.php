<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categoria'; // aca se le llama a la tabla que usaremos
    protected $fillable = ['nombre', 'descripcion', 'condicion']; // el modelo de la db

    public function articulos(){
        return $this->hasMany('app\Articulo');
    }

}
