<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = config('tailwind-builder.tables.settings', 'twb_settings');

        if (Schema::hasTable($tableName)) {
            return;
        }

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();

            /*
             * Setting key examples:
             * default_homepage_id
             * default_header_component_id
             * default_footer_component_id
             * navigation_source
             * theme
             */
            $table->string('key')->unique();

            /*
             * Setting value is flexible.
             * Store strings, booleans, arrays, or objects as JSON.
             */
            $table->json('value')->nullable();

            /*
             * Optional grouping for builder UI.
             * Examples:
             * general, navigation, theme, seo
             */
            $table->string('group')->default('general')->index();

            /*
             * Whether this setting is managed internally by package.
             */
            $table->boolean('is_system')->default(false)->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('tailwind-builder.tables.settings', 'twb_settings'));
    }
};
