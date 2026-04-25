<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Add new columns for start and end times, placing them after the date
            $table->time('start_time')->after('reservation_date');
            $table->time('end_time')->after('start_time');

            // Drop the old single time column
            $table->dropColumn('reservation_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Re-add the old column if we need to roll back
            $table->time('reservation_time')->after('reservation_date');

            // Drop the new columns
            $table->dropColumn('start_time');
            $table->dropColumn('end_time');
        });
    }
};
