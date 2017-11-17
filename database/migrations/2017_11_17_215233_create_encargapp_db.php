<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEncargappDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('nombre')->nullable();
            $table->string('apellido')->nullable();
            $table->string('email')->unique();
            $table->string('email_token')->nullable();
            $table->string('telefono')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->string('password')->nullable();
            $table->integer('status')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });
        
         Schema::create('relaciones', function (Blueprint $table) {
             $table->integer('id_usuario')->unsigned();
             $table->integer('id_contacto')->unsigned();
             $table->integer('status')->unsigned();
             $table->timestamps();
             $table->primary(['id_usuario', 'id_contacto']);
        });
        
        Schema::create('encargos', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->text('encargo');
            $table->integer('id_asignador')->unsigned();
            $table->integer('id_responsable')->unsigned();		
            $table->boolean('visto');
            $table->boolean('mute')->default(0);
            $table->dateTime('ultima_notificacion')->nullable()->default(null);
            $table->dateTime('fecha_plazo');
            $table->dateTime('fecha_conclusion')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('comentarios', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->text('comentario');
            $table->integer('id_usuario')->unsigned();
            $table->integer('id_encargo')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('usuarios_password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at')->nullable();
        });
        
        Schema::table('relaciones', function (Blueprint $table) {
            $table->foreign('id_usuario')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('id_contacto')->references('id')->on('usuarios')->onDelete('cascade');
        });
        
        Schema::table('comentarios', function (Blueprint $table) {
            $table->foreign('id_usuario')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('id_encargo')->references('id')->on('encargos')->onDelete('cascade');
        });
        
        Schema::table('encargos', function (Blueprint $table) {
            $table->foreign('id_responsable')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('id_asignador')->references('id')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('relaciones');
        Schema::drop('encargos');
        Schema::drop('comentarios');
        Schema::drop('usuarios');
        Schema::drop('usuarios_password_resets');
    }
}
