<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('video_user_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['like','dislike']); // loại vote
            $table->timestamps();

            $table->unique(['video_id','user_id']); // mỗi user chỉ vote 1 lần
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_user_votes');
    }
};