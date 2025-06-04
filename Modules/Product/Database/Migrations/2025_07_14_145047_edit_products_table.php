<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            //add product karat

            $table->integer('product_cost')->default(0)->change();
            $table->integer('product_price')->default(0)->change();
            $table->string('product_type')->default('Normal')->after('product_cost');
            $table->double('product_weight', 8, 2)->default(0)->after('product_type');
            $table->double('product_purity', 8, 2)->default(0)->after('product_weight');
            $table->unsignedBigInteger('supplier_id')->nullable()->after('category_id');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->restrictOnDelete();
            $table->unsignedBigInteger('customer_id')->nullable()->after('supplier_id');
            $table->foreign('customer_id')->references('id')->on('customers')->restrictOnDelete();
        });
    }
    //wlee
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropForeign(['customer_id']);
            $table->dropColumn(['product_type', 'product_weight', 'supplier_id', 'customer_id']);
            $table->integer('product_cost')->change();
            $table->integer('product_price')->change();
        });
    }
}
