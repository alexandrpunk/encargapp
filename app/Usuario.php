<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Notifications\Notifiable;
use App\Notifications\RestablecerPassword;
use Illuminate\Database\Eloquent\SoftDeletes;


class Usuario extends Model implements AuthenticatableContract, CanResetPasswordContract {
    #use Authenticatable, CanResetPassword, SoftDeletes;
    use Authenticatable, CanResetPassword, Notifiable, SoftDeletes;

    protected $table = 'usuarios';
    protected $fillable = [
        'nombre', 'apellido', 'email', 'email_token', 'telefono', 'password', 'status'
    ];
    protected $hidden = [
        'password', 'remember_token', 'email_token', 'created_at', 'updated_at', 'deleted_at'
    ];
    protected $dates = ['deleted_at'];

    // protected $appends = ['contactos' => 'hola mundo'];

    public function sendPasswordResetNotification($token) {
        $this->notify(new RestablecerPassword($token));
    }
    public function setPasswordAttribute($value) {
        $this->attributes['password'] = app('hash')->make($value);
    }
    public function setNombreAttribute($value) {
        $this->attributes['nombre'] =strtolower($value);
    }
    public function setApellidoAttribute($value) {
        $this->attributes['apellido'] =strtolower($value);
    }
    public function setEmailAttribute($value) {
        $this->attributes['email'] =strtolower($value);
    }

    public function verificado() {
        $this->status = 1;
        $this->email_token = null;
        $this->save();
    }
    
    public function encargos() {
        return $this->hasMany('App\Encargo', 'id_asignador');
    }
    
    public function pendientes() {
        return $this->hasMany('App\Encargo', 'id_responsable');
    }
    
    public function contactos() {
        return $this->hasMany('App\Relacion', 'id_usuario');
    }
        
    public function comentarios() {
        return $this->hasMany('App\Comentario', 'id_usuario');
    }
}
