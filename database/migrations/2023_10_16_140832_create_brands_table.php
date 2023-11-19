<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();

            $table->boolean('on_home_page')->default(false);

            $table->integer('sorting')->default(999);

            $table->string('slug')->unique();

            $table->string('title');

            $table->string('thumbnail')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        if (app()->isLocal()) {
            Schema::dropIfExists('brands');
        }
    }
};
