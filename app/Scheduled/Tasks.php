<?php

namespace App\Scheduled;

/**
 * Organized scheduled task definition
 *
 * Update app\Console\Kernel.php, schedule() function, change to:
 * App\Scheduled\Tasks::schedule($schedule);
 */

use Illuminate\Console\Scheduling\Schedule;

class Tasks
{
    public static function schedule(Schedule $schedule)
    {
        self::statAggregationTasks($schedule);
        self::notificationTasks($schedule);
        self::housekeepingTasks($schedule);
    }

    // ---------------------------------------------------------------------

    protected static function statAggregationTasks(Schedule $schedule)
    {

    }

    protected static function notificationTasks(Schedule $schedule)
    {

    }

    protected static function housekeepingTasks(Schedule $schedule)
    {
        // Flush expired password reset tokens
        $schedule->command("auth:clear-resets")->dailyAt('3:33');

        // Reset any hung store update_started_at values
        $schedule->command('reset-update-statuses')->everyMinute();
    }
}
