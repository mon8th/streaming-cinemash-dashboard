<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('streaming_providers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained()->onDelete('cascade');
            $table->string('provider_name');
            $table->string('provider_id');
            $table->string('logo_path')->nullable();
            $table->string('type');
            $table->string('region', 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('streaming_providers');
    }
};
