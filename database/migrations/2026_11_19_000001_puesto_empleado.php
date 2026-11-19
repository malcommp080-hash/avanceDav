<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// RF005: el registro de empleados captura su puesto (además del rol que define permisos)
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empleado', fn (Blueprint $t) => $t->string('Puesto', 40)->nullable()->after('Direccion'));
    }

    public function down(): void
    {
        Schema::table('empleado', fn (Blueprint $t) => $t->dropColumn('Puesto'));
    }
};
