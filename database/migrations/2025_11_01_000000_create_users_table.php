<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateUsersTable extends Migration {
    public function up(){ Schema::create('users', function(Blueprint $table){ $table->id(); $table->string('name'); $table->string('email')->unique(); $table->string('password'); $table->enum('role',['coordinator','encargado'])->default('encargado'); $table->boolean('active')->default(true); $table->timestamps(); }); }
    public function down(){ Schema::dropIfExists('users'); }
}
