<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductosTable extends Migration
{
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            // Primero eliminamos la columna precio existente
            $table->dropColumn('precio');

            // Agregamos las nuevas columnas
            $table->decimal('costo_compra', 10, 2)->after('descripcion');
            $table->decimal('precio_alquiler', 10, 2)->after('costo_compra');
        });
    }

    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            // Revertimos los cambios
            $table->dropColumn(['costo_compra', 'precio_alquiler']);
            $table->decimal('precio', 10, 2)->after('descripcion');
        });
    }
}
