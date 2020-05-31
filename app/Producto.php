<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'total'
    ];

    public function guia()
    {
        return $this->belongsTo('App\Guia', 'productos_id','id');
    }
}
