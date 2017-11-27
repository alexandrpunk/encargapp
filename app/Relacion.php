<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Relacion extends Model {
    protected $table = 'relaciones';
    public $incrementing = false;
    protected $primaryKey = null;
    protected $fillable = [
        'id_usuario', 'id_contacto', 'status'
    ];
    protected $hidden = [
       'created_at', 'updated_at', 'deleted_at'
    ];
    
    public function usuario() {
        return $this->hasOne('App\Usuario', 'id', 'id_usuario');
    }
    public function contacto() {
        return $this->hasOne('App\Usuario', 'id', 'id_contacto');
    }
}