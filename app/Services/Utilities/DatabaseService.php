<?php

namespace App\Services\Utilities;

use Illuminate\Support\Facades\DB;

class DatabaseService
{
    /**
     * Add a description to a specified database table
     *
     * @param string $table Table name to modify
     * @param string $comment Description to add to table
     * @param string|null $connection (optional) Database connection to access table through
     * @return bool Success
     */
    public function setTableComment(string $table, string $comment, string $connection = null)
    {
        // Sqlite doesn't support table comments
        if (! $this->isSQLiteConnection($connection)) {
            DB::unprepared("ALTER TABLE ".$table." COMMENT='" . $comment . "';");
            return true;
        }

        return false;
    }

    /**
     * Returns whether or not the specified db connection is SQLite
     *
     * @param  string  $connection Name of db connection to test (optional; default if not specified)
     * @return bool Is SQLite Connection
     */
    public function isSQLiteConnection(string $connection = null)
    {
        $connection = $connection ?? config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        return ($driver == 'sqlite');
    }
}
