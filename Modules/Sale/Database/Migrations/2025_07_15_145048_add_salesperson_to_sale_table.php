<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSalespersonToSaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->unsignedBigInteger('sales_person_id')->nullable()->after('customer_name');
            $table->string('sales_person_name')->nullable()->after('sales_person_id');
            $table->string('customer_name')->nullable()->change();
            $table->foreign('sales_person_id')->references('id')->on('sales_persons')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['sales_person_id']);
            $table->dropColumn('sales_person_id');
            $table->dropColumn('sales_person_name');
        });
    }
}
