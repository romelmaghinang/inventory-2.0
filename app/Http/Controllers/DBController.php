<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class DBController extends Controller
{
    public function databaseExists($name)
    {
        return DB::table('users')->where('database_name', '=', $name)->first();
    }

    public function createDatabase($name)
    {
        DB::statement("CREATE DATABASE `$name`");
    }

    public function runMigrations($databaseName, $path)
    {
        $this->setDatabaseConnection($databaseName);
        Artisan::call('migrate', [
            '--path' => $path,
            '--database' => $databaseName,
            '--force' => true
        ]);

        return Artisan::output();
    }

    public function createUserDatabase($databaseName)
    {
        $dbController = new DBController();
        $dbController->createDatabase($databaseName);

        $migrationsPath = 'database/migrations/user_database';

        return $dbController->runMigrations($databaseName, $migrationsPath);
    }
    protected function setDatabaseConnection($databaseName)
    {
        config([
            'database.connections.' . $databaseName => [
                'driver' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => $databaseName,
                'username' => env('DB_USERNAME', 'sharpelp'),
                'password' => env('DB_PASSWORD', 'Sharpe0207$'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ],
        ]);

        DB::purge($databaseName);
        DB::reconnect($databaseName);
    }
}
