<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku', 80)->nullable()->unique();
            $table->string('barcode', 120)->nullable()->unique();
            $table->string('seo_title', 70)->nullable();
            $table->string('seo_description', 320)->nullable();
            $table->text('seo_keywords')->nullable();
        });

        DB::table('products')
            ->whereNull('sku')
            ->orderBy('id')
            ->each(function (object $product): void {
                DB::table('products')
                    ->where('id', $product->id)
                    ->update(['sku' => 'KJ-'.str_pad((string) $product->id, 6, '0', STR_PAD_LEFT)]);
            });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['sku']);
            $table->dropUnique(['barcode']);
            $table->dropColumn(['sku', 'barcode', 'seo_title', 'seo_description', 'seo_keywords']);
        });
    }
};
