<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResetPassword extends Model {
    
    protected $table = 'usuarios_password_resets';
    public $incrementing = false;
    protected $primaryKey = null;
    protected $fillable = [
        'email', 'token'
    ];
    protected $hidden = [
        'created_at'
    ];
    
    public function usuario() {
        return $this->belongsTo('App\Usuario', 'email', 'email');
    }
}