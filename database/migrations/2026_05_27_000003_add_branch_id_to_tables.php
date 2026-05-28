<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete()->after('role');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete()->after('cashier_id');
        });

        Schema::table('stock_ins', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete()->after('created_by');
        });

        Schema::table('stock_outs', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete()->after('created_by');
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete()->after('user_id');
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete()->after('created_by');
        });

        Schema::table('held_carts', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete()->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', fn (Blueprint $t) => $t->dropForeign(['branch_id']));
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('branch_id'));

        Schema::table('sales', fn (Blueprint $t) => $t->dropForeign(['branch_id']));
        Schema::table('sales', fn (Blueprint $t) => $t->dropColumn('branch_id'));

        Schema::table('stock_ins', fn (Blueprint $t) => $t->dropForeign(['branch_id']));
        Schema::table('stock_ins', fn (Blueprint $t) => $t->dropColumn('branch_id'));

        Schema::table('stock_outs', fn (Blueprint $t) => $t->dropForeign(['branch_id']));
        Schema::table('stock_outs', fn (Blueprint $t) => $t->dropColumn('branch_id'));

        Schema::table('stock_movements', fn (Blueprint $t) => $t->dropForeign(['branch_id']));
        Schema::table('stock_movements', fn (Blueprint $t) => $t->dropColumn('branch_id'));

        Schema::table('purchase_orders', fn (Blueprint $t) => $t->dropForeign(['branch_id']));
        Schema::table('purchase_orders', fn (Blueprint $t) => $t->dropColumn('branch_id'));

        Schema::table('held_carts', fn (Blueprint $t) => $t->dropForeign(['branch_id']));
        Schema::table('held_carts', fn (Blueprint $t) => $t->dropColumn('branch_id'));
    }
};
