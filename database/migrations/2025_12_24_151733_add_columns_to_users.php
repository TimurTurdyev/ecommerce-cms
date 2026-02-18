<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 32)->nullable()->index();
            $table->string('token')->nullable()->after('remember_token')->unique();
        });

        \App\Models\User::query()
            ->create([
                'id' => 1,
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('admin@example.com'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);

        \App\Models\User::query()
            ->where(['id' => 1])
            ->update(['role' => 'admin']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropColumn('token');
        });
    }
};
