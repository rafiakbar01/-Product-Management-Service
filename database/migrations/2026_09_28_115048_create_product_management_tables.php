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
        // 1. Table Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Table Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('sku', 64)->unique();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->enum('product_type', ['physical', 'digital', 'service'])->default('physical');
            $table->decimal('price', 12, 2);
            $table->decimal('cost_price', 12, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->integer('min_stock_alert')->default(5);
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active');
            $table->json('attributes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Composite indexes for high performance search & filter
            $table->index(['name', 'sku']);
            $table->index(['status', 'stock']);
            $table->index('price');
        });

        // 3. Table Product Activity Logs
        Schema::create('product_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('action', 50); // CREATE, READ, UPDATE, DELETE, SEARCH, LOW_STOCK_ALERT, ERROR
            $table->string('description');
            $table->string('user_identifier', 100)->default('Internal Assessor');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('payload')->nullable();
            $table->string('response_status', 20)->default('SUCCESS');
            $table->double('execution_time_ms', 8, 2)->default(0.00);
            $table->timestamp('created_at')->useCurrent();

            $table->index(['action', 'created_at']);
        });

        // 4. Table Performance Metrics
        Schema::create('performance_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('route_name')->nullable();
            $table->string('method', 10);
            $table->string('url', 255);
            $table->double('response_time_ms', 8, 2);
            $table->double('memory_usage_mb', 8, 2);
            $table->integer('db_query_count')->default(0);
            $table->integer('status_code')->default(200);
            $table->timestamp('created_at')->useCurrent();

            $table->index(['created_at', 'status_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_metrics');
        Schema::dropIfExists('product_activity_logs');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
