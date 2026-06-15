<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = config('tailwind-builder.tables.revisions', 'twb_revisions');

        if (Schema::hasTable($tableName)) {
            return;
        }

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();

            /*
             * Flexible revision target.
             *
             * Examples:
             * revisionable_type = page
             * revisionable_id   = 1
             *
             * revisionable_type = component
             * revisionable_id   = 4
             */
            $table->string('revisionable_type', 50);
            $table->unsignedBigInteger('revisionable_id');

            /*
             * Human-readable revision title.
             * Example: "Before publishing homepage"
             */
            $table->string('title')->nullable();

            /*
             * Main content snapshot.
             */
            $table->json('content_json')->nullable();

            /*
             * Full snapshot of important fields at revision time.
             * This lets us restore more than just content_json later.
             */
            $table->json('snapshot_json')->nullable();

            /*
             * Optional reason/source.
             * Examples:
             * manual_save, autosave, publish, restore
             */
            $table->string('source', 50)->default('manual_save')->index();

            /*
             * No foreign key to users table.
             */
            $table->unsignedBigInteger('created_by_id')->nullable()->index();

            $table->timestamps();

            $table->index(
                ['revisionable_type', 'revisionable_id'],
                'twb_revisions_revisionable_index'
            );

            $table->index(
                ['revisionable_type', 'revisionable_id', 'created_at'],
                'twb_revisions_revisionable_created_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('tailwind-builder.tables.revisions', 'twb_revisions'));
    }
};
