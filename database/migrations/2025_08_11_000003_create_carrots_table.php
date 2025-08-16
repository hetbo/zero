<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carrots', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('length');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carrots');
    }

/*    private function getUserTableName(): string
    {
        // Option 1: Use configured table name
        $configuredTable = config('zero.user_table');
        if ($configuredTable) {
            return $configuredTable;
        }

        // Option 2: Auto-detect from model (if enabled)
        if (config('zero.auto_detect_user_table', false)) {
            $userModel = config('zero.user_model', 'App\\Models\\User');

            if (class_exists($userModel)) {
                return (new $userModel)->getTable();
            }
        }

        // Fallback
        return 'users';
    }*/
};