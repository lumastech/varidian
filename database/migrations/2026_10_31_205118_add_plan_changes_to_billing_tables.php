<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('type')->default('subscription')->after('subscription_id');
            $table->foreignId('plan_id')->nullable()->after('type')->constrained()->restrictOnDelete();
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreignId('scheduled_plan_id')->nullable()->after('plan_id')->constrained('plans')->nullOnDelete();
            $table->timestamp('current_period_starts_at')->nullable()->after('status');
        });

        DB::table('subscriptions')->whereNotNull('current_period_ends_at')->orderBy('id')->each(function (object $subscription) {
            DB::table('subscriptions')->where('id', $subscription->id)->update([
                'current_period_starts_at' => Date::parse($subscription->current_period_ends_at)->subMonthNoOverflow(),
            ]);
        });

        DB::table('invoices')->whereNotNull('subscription_id')->update([
            'plan_id' => DB::raw('(select plan_id from subscriptions where subscriptions.id = invoices.subscription_id)'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('scheduled_plan_id');
            $table->dropColumn('current_period_starts_at');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropConstrainedForeignId('plan_id');
            $table->dropColumn('type');
        });
    }
};
