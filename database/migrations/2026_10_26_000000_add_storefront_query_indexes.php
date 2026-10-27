<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->index(['category_id', 'is_active', 'name'], 'products_category_active_name_index');
            $table->index(['is_active', 'is_featured', 'updated_at'], 'products_active_featured_updated_index');
            $table->index(['is_active', 'name'], 'products_active_name_index');
            $table->index('updated_at', 'products_updated_at_index');
        });

        Schema::table('orders', function (Blueprint $table): void {
            $table->index('created_at', 'orders_created_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropIndex('orders_created_at_index');
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->dropIndex('products_category_active_name_index');
            $table->dropIndex('products_active_featured_updated_index');
            $table->dropIndex('products_active_name_index');
            $table->dropIndex('products_updated_at_index');
        });
    }
};
