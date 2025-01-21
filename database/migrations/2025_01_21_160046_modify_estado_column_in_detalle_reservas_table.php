<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyEstadoColumnInDetalleReservasTable extends Migration
{
    public function up()
    {
        Schema::table('detalle_reservas', function (Blueprint $table) {
            // Primero modificamos la columna existente
            DB::statement('ALTER TABLE detalle_reservas MODIFY estado BOOLEAN DEFAULT 1');
        });
    }

    public function down()
    {
        Schema::table('detalle_reservas', function (Blueprint $table) {
            // Revertir a la configuración anterior si es necesario
            DB::statement('ALTER TABLE detalle_reservas MODIFY estado VARCHAR(255) DEFAULT "activo"');
        });
    }
}
