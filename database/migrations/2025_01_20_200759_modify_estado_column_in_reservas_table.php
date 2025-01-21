<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyEstadoColumnInReservasTable extends Migration
{
    public function up()
    {
        // Primero agregamos una columna temporal
        Schema::table('reservas', function (Blueprint $table) {
            $table->integer('estado_nuevo')->nullable();
        });

        // Convertimos los valores existentes
        DB::table('reservas')->update([
            'estado_nuevo' => DB::raw("CASE
                WHEN estado = 'pendiente' THEN 1
                WHEN estado = 'confirmado' THEN 2
                WHEN estado = 'cancelado' THEN 3
                WHEN estado = 'entregado' THEN 4
                WHEN estado = 'retirado' THEN 5
                ELSE 1 END")
        ]);

        // Eliminamos la columna vieja
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn('estado');
        });

        // Renombramos la nueva columna
        Schema::table('reservas', function (Blueprint $table) {
            $table->renameColumn('estado_nuevo', 'estado');
        });

        // Hacemos la columna no nullable y con valor por defecto
        Schema::table('reservas', function (Blueprint $table) {
            $table->integer('estado')->default(1)->change();
        });
    }

    public function down()
    {
        // Primero agregamos una columna temporal
        Schema::table('reservas', function (Blueprint $table) {
            $table->string('estado_viejo')->nullable();
        });

        // Convertimos los valores de vuelta a strings
        DB::table('reservas')->update([
            'estado_viejo' => DB::raw("CASE
                WHEN estado = 1 THEN 'pendiente'
                WHEN estado = 2 THEN 'confirmado'
                WHEN estado = 3 THEN 'cancelado'
                WHEN estado = 4 THEN 'entregado'
                WHEN estado = 5 THEN 'retirado'
                ELSE 'pendiente' END")
        ]);

        // Eliminamos la columna numérica
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn('estado');
        });

        // Renombramos la columna string
        Schema::table('reservas', function (Blueprint $table) {
            $table->renameColumn('estado_viejo', 'estado');
        });

        // Hacemos la columna no nullable y con valor por defecto
        Schema::table('reservas', function (Blueprint $table) {
            $table->string('estado')->default('pendiente')->change();
        });
    }
}
