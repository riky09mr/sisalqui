<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePrecioTotalFromDetalleReservas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detalle_reservas', function (Blueprint $table) {
            $table->dropColumn('precio_total');
        });
    }

    public function down()
    {
        Schema::table('detalle_reservas', function (Blueprint $table) {
            $table->decimal('precio_total', 10, 2)->after('precio_unitario');
        });
    }
}
