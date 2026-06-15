<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = config('tailwind-builder.tables.assets', 'twb_assets');

        if (Schema::hasTable($tableName)) {
            return;
        }

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();

            /*
             * Storage disk and path.
             * Example:
             * disk: public
             * path: tailwind-builder/images/example.jpg
             */
            $table->string('disk')->default(config('tailwind-builder.storage_disk', 'public'));
            $table->string('path');

            /*
             * filename = stored/generated filename
             * original_filename = user uploaded filename
             */
            $table->string('filename');
            $table->string('original_filename')->nullable();

            $table->string('mime_type')->nullable()->index();
            $table->unsignedBigInteger('size')->nullable();

            /*
             * Asset type examples:
             * image, video, document, icon
             */
            $table->string('type', 50)->default('image')->index();

            /*
             * SEO/accessibility.
             */
            $table->string('alt_text')->nullable();
            $table->text('caption')->nullable();

            /*
             * Extra metadata:
             * width, height, thumbnails, dominant color, etc.
             */
            $table->json('metadata_json')->nullable();

            /*
             * No foreign key to users table.
             */
            $table->unsignedBigInteger('created_by_id')->nullable()->index();

            $table->softDeletes();
            $table->timestamps();

            $table->index(['disk', 'path']);
            $table->index(['type', 'mime_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('tailwind-builder.tables.assets', 'twb_assets'));
    }
};
