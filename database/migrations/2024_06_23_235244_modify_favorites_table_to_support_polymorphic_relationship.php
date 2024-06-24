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
        Schema::table('favorites', function (Blueprint $table) {
            $table->renameColumn('post_id', 'favoritable_id');
            $table->string('favoritable_type')->after('post_id')->default('App\Models\Post');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->renameColumn('favoritable_id', 'post_id');
            $table->dropColumn('favoritable_type');
        });
    }
};
