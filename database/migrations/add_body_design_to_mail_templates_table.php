<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $table = config('laravel-mail.database.tables.mail_templates', 'mail_templates');

        if (! Schema::hasColumn($table, 'body_design')) {
            Schema::table($table, function (Blueprint $table) {
                $table->json('body_design')->nullable()->after('html_body');
            });
        }
    }

    public function down(): void
    {
        $table = config('laravel-mail.database.tables.mail_templates', 'mail_templates');

        if (Schema::hasColumn($table, 'body_design')) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('body_design');
            });
        }
    }
};
