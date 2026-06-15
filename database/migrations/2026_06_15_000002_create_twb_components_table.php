<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = config('tailwind-builder.tables.components', 'twb_components');

        if (Schema::hasTable($tableName)) {
            return;
        }

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug');

            /*
             * header  = reusable site/page header
             * footer  = reusable site/page footer
             * section = page block like hero, pricing, FAQ
             * element = smaller item like button, heading, image
             * global  = reusable shared component
             */
            $table->string('type', 50)->default('section')->index();

            $table->string('ui_preset', 50)->default('daisyui')->index();

            /*
             * Optional grouping in the builder UI.
             * Examples:
             * hero, pricing, testimonials, navigation, forms
             */
            $table->string('category')->nullable()->index();

            /*
             * active   = available for use
             * draft    = being edited
             * archived = hidden but retained
             */
            $table->string('status', 50)->default('active')->index();

            /*
             * Component structure.
             */
            $table->json('content_json')->nullable();

            /*
             * Editor/design metadata.
             * Examples:
             * allowed controls, responsive behavior, default options.
             */
            $table->json('settings_json')->nullable();

            $table->string('preview_image')->nullable();

            /*
             * is_system = shipped by package
             * is_global = edits affect all linked uses
             */
            $table->boolean('is_system')->default(false)->index();
            $table->boolean('is_global')->default(false)->index();

            /*
             * No foreign keys to users table.
             */
            $table->unsignedBigInteger('created_by_id')->nullable()->index();
            $table->unsignedBigInteger('updated_by_id')->nullable()->index();

            $table->softDeletes();
            $table->timestamps();

            $table->unique(['slug', 'type']);
            $table->index(['category', 'type']);
            $table->index(['type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('tailwind-builder.tables.components', 'twb_components'));
    }
};
