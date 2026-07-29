<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Проверяем наличие колонок перед удалением
        if (Schema::hasColumn('users', 'faculty')) {
            $table->dropColumn('faculty');
        }
        if (Schema::hasColumn('users', 'specialty')) {
            $table->dropColumn('specialty');
        }
        if (Schema::hasColumn('users', 'group')) {
            $table->dropColumn('group');
        }

        // Добавляем внешнюю связь
        $table->foreignId('group_id')->nullable()->after('email')->constrained()->nullOnDelete();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn('group_id');
            $table->string('faculty')->nullable();
            $table->string('specialty')->nullable();
            $table->string('group')->nullable();
         });
    }
};
