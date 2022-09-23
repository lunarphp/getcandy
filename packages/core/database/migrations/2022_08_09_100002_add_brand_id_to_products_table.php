<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class AddBrandIdToProductsTable extends Migration
{
    public function up()
    {
        Schema::table($this->prefix.'products', function (Blueprint $table) {
            $table->foreignId('brand_id')->after('id')
                  ->nullable()
                  ->constrained($this->prefix.'brands');
        });
    }

    public function down()
    {
        Schema::table($this->prefix.'products', function ($table) {
            $table->dropColumn('brand_id');
        });
    }
}
