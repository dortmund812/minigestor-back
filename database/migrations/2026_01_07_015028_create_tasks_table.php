<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title');
            $table->string('description');
            $table->boolean('status')->default(1);
            $table->enum('task_status', ['pending', 'in_progress', 'done'])->default('pending');
            $table->foreignId('user_creator')->constrained('users');
            $table->foreignId('user_assigned')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('tasks');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
