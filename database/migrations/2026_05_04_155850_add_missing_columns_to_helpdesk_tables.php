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
        Schema::table('categories', function (Blueprint $table) {
            if (! Schema::hasColumn('categories', 'name')) {
                $table->string('name')->after('id');
            }
        });

        Schema::table('tickets', function (Blueprint $table) {
            if (! Schema::hasColumn('tickets', 'ticket_no')) {
                $table->string('ticket_no')->unique()->after('id');
            }

            if (! Schema::hasColumn('tickets', 'user_id')) {
                $table->foreignId('user_id')->after('ticket_no')->constrained()->onDelete('cascade');
            }

            if (! Schema::hasColumn('tickets', 'category_id')) {
                $table->foreignId('category_id')->nullable()->after('user_id')->constrained();
            }

            if (! Schema::hasColumn('tickets', 'title')) {
                $table->string('title')->after('category_id');
            }

            if (! Schema::hasColumn('tickets', 'description')) {
                $table->text('description')->after('title');
            }

            if (! Schema::hasColumn('tickets', 'status')) {
                $table->enum('status', ['Open', 'On Progress', 'Resolved', 'Closed'])
                    ->default('Open')
                    ->after('description');
            }
        });

        Schema::table('ticket_logs', function (Blueprint $table) {
            if (! Schema::hasColumn('ticket_logs', 'ticket_id')) {
                $table->foreignId('ticket_id')->after('id')->constrained()->onDelete('cascade');
            }

            if (! Schema::hasColumn('ticket_logs', 'user_id')) {
                $table->foreignId('user_id')->after('ticket_id')->constrained();
            }

            if (! Schema::hasColumn('ticket_logs', 'status')) {
                $table->string('status')->after('user_id');
            }

            if (! Schema::hasColumn('ticket_logs', 'note')) {
                $table->text('note')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_logs', function (Blueprint $table) {
            if (Schema::hasColumn('ticket_logs', 'note')) {
                $table->dropColumn('note');
            }

            if (Schema::hasColumn('ticket_logs', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('ticket_logs', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }

            if (Schema::hasColumn('ticket_logs', 'ticket_id')) {
                $table->dropConstrainedForeignId('ticket_id');
            }
        });

        Schema::table('tickets', function (Blueprint $table) {
            if (Schema::hasColumn('tickets', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('tickets', 'description')) {
                $table->dropColumn('description');
            }

            if (Schema::hasColumn('tickets', 'title')) {
                $table->dropColumn('title');
            }

            if (Schema::hasColumn('tickets', 'category_id')) {
                $table->dropConstrainedForeignId('category_id');
            }

            if (Schema::hasColumn('tickets', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }

            if (Schema::hasColumn('tickets', 'ticket_no')) {
                $table->dropUnique(['ticket_no']);
                $table->dropColumn('ticket_no');
            }
        });

        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};
