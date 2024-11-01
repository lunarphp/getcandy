<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::table($this->prefix.'prices', function (Blueprint $table) {
            $table->json('attribute_data')->nullable()->after('min_quantity');
        });
    }

    public function down()
    {
        Schema::table($this->prefix.'prices', function ($table) {
            $table->dropColumn('attribute_data');
        });
    }
};
