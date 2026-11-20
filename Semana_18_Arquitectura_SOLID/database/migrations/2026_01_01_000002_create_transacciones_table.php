<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {Schema::create('transacciones',function(Blueprint $t){$t->id();$t->foreignId('comercio_id')->constrained()->cascadeOnDelete();$t->decimal('monto',12,2);$t->string('metodo');$t->decimal('comision',12,2);$t->string('estado');$t->timestamps();});}
 public function down(): void {Schema::dropIfExists('transacciones');}
};
