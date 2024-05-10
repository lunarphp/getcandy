<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;
use Lunar\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->prefix.'products', function (Blueprint $table) {
            $table->foreignId('brand_id')->after('id')
                ->nullable()
                ->constrained($this->prefix.'brands');
        });

        Schema::table($this->prefix.'products', function (Blueprint $table) {
            if (Schema::hasIndex($this->prefix.'products', ['brand'])) {
                $table->dropIndex(['brand']);
            }
            $table->dropColumn('brand');
        });
    }

    public function down(): void
    {
        Schema::table($this->prefix.'products', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['brand_id']);
            }
            $table->dropColumn('brand_id');
        });
    }
};
