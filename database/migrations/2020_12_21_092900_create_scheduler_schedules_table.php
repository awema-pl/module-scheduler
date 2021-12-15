
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulerSchedulesTable extends Migration
{
    public function up()
    {
        Schema::create(config('scheduler.database.tables.scheduler_schedules'), function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('cron');
            $table->boolean('activated')->index();
            $table->morphs('schedulable');
            $table->timestamps();
        });

        Schema::table(config('scheduler.database.tables.scheduler_schedules'), function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained(config('scheduler.database.tables.users'))
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table(config('scheduler.database.tables.scheduler_schedules'), function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::drop(config('scheduler.database.tables.scheduler_schedules'));
    }
}
