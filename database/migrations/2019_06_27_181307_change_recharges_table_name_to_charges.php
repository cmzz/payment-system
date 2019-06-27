<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeRechargesTableNameToCharges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('recharges', 'charges');

        Schema::table('charges', function (Blueprint $table) {
            $table->string('charge_no')->nullable()->comment('支付系统单号');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('charges', 'recharges');

        Schema::table('charges', function (Blueprint $table) {
            $table->dropColumn('charge_no');
        });
    }
}
