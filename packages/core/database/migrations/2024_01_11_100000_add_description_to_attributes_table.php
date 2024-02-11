<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class AddDescriptionToAttributesTable extends Migration
{
    public function up()
    {
        Schema::table($this->prefix.'attributes', function (Blueprint $table) {
            $table->json('description')->after('name');
        });
    }

    public function down()
    {
        Schema::table($this->prefix.'attributes', function ($table) {
            $table->dropColumn('description');
        });
    }
}
