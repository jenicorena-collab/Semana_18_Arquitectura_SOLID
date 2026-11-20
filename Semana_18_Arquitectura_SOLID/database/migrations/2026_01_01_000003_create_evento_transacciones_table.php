<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {Schema::create('evento_transacciones',function(Blueprint $t){$t->id();$t->foreignId('transaccion_id')->constrained('transacciones')->cascadeOnDelete();$t->string('tipo');$t->string('detalle')->nullable();$t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();$t->timestamps();});}
 public function down(): void {Schema::dropIfExists('evento_transacciones');}
};
