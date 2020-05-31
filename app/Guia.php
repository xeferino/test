<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guia extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'numero_guia', 'descripcion'
    ];

    public function producto()
    {
        return $this->hasMany('App\Producto', 'productos_id','id');
    }
}
