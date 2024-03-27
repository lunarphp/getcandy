<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

class CreateBrandCollectionTable extends Migration
{
    public function up()
    {
        Schema::create($this->prefix.'brand_collection', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained($this->prefix.'brands');
            $table->foreignId('collection_id')->constrained($this->prefix.'collections');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->prefix.'brand_collection');
    }
}
