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
            // Notification settings
            $table->boolean('email_notifications')->default(true);
            $table->boolean('document_upload_notifications')->default(true);
            $table->boolean('review_notifications')->default(true);
            $table->boolean('system_notifications')->default(true);
            
            // Appearance settings
            $table->string('theme')->default('light'); // light, dark, auto
            $table->string('language')->default('id'); // id, en
            $table->boolean('sidebar_collapsed')->default(false);
            $table->boolean('compact_mode')->default(false);
            
            // Security settings
            $table->timestamp('last_password_change')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'email_notifications',
                'document_upload_notifications',
                'review_notifications',
                'system_notifications',
                'theme',
                'language',
                'sidebar_collapsed',
                'compact_mode',
                'last_password_change',
                'last_login_at',
                'last_login_ip'
            ]);
        });
    }
}; 