<?php

namespace Iocaste\Microservice\Foundation\Seeder;

use DB;
use Illuminate\Database\Seeder as IlluminateSeeder;

/**
 * Class Seeder
 */
abstract class Seeder extends IlluminateSeeder
{
    /**
     * Truncates all tables except migrations.
     */
    public function truncateTables()
    {
        $dbName = env('DB_DATABASE');

        // Get all tables list, except migrations table
        $tables = DB::select('SHOW TABLES WHERE `Tables_in_'.$dbName.'` != ?', ['migrations']);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        foreach ($tables as $table) {
            DB::table($table->{'Tables_in_' . $dbName})->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * @param $path
     *
     * @return array
     */
    public function loadData($path): array
    {
        $jsonFile = database_path($path);

        return json_decode(file_get_contents($jsonFile), true);
    }

    /**
     * @param $array
     *
     * @return string
     */
    public function encodeJson($array): string
    {
        return json_encode($array, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
