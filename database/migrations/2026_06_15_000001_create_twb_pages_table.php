<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = config('tailwind-builder.tables.pages', 'twb_pages');

        if (Schema::hasTable($tableName)) {
            return;
        }

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();

            /*
             * A page is a routeable document.
             * Examples:
             * /home
             * /about
             * /services
             * /contact
             */
            $table->string('title');
            $table->string('slug');

            /*
             * page    = normal routeable frontend page
             * landing = standalone landing page
             */
            $table->string('type', 50)->default('page');

            /*
             * draft     = editable but not public
             * published = visible/renderable
             * archived  = hidden but retained
             */
            $table->string('status', 50)->default('draft');

            /*
             * Page body structure.
             * Later this will be edited visually by the builder.
             */
            $table->json('content_json')->nullable();

            /*
             * Simple v1 navigation support.
             * Advanced menus can come later with twb_menus and twb_menu_items.
             */
            $table->boolean('show_in_navigation')->default(false)->index();
            $table->string('navigation_label')->nullable();
            $table->unsignedInteger('navigation_order')->default(0)->index();
            $table->unsignedBigInteger('navigation_parent_id')->nullable()->index();

            /*
             * SEO fields.
             */
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_image')->nullable();

            /*
             * Homepage support.
             */
            $table->boolean('is_homepage')->default(false)->index();

            /*
             * System pages are protected package/internal pages.
             */
            $table->boolean('is_system')->default(false)->index();

            $table->timestamp('published_at')->nullable()->index();

            /*
             * No foreign keys to users table.
             * Host apps may use custom auth/user structures.
             */
            $table->unsignedBigInteger('created_by_id')->nullable()->index();
            $table->unsignedBigInteger('updated_by_id')->nullable()->index();

            $table->softDeletes();
            $table->timestamps();

            $table->unique(['slug', 'type']);
            $table->index(['type', 'status']);
            $table->index(['status', 'published_at']);
            $table->index(['show_in_navigation', 'navigation_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('tailwind-builder.tables.pages', 'twb_pages'));
    }
};
