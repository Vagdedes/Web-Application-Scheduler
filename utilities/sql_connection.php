<?php
require_once '/var/www/.structure/library/base/sql.php';
$current_sql_database = null;
$previous_sql_database = null;
$sql_database_directory = null;
load_sql_database();

class SqlDatabaseCredentials
{
    public const
        STORAGE = "sql_storage_credentials",
        MEMORY = "sql_memory_credentials";
}

function load_previous_sql_database(): void
{
    global $previous_sql_database;

    if ($previous_sql_database !== null) {
        global $current_sql_database;
        $current_sql_database = $previous_sql_database;
        $previous_sql_database = null;
        set_sql_credentials($current_sql_database[0],
            $current_sql_database[1],
            $current_sql_database[2],
            null,
            null,
            null,
            true);
    } else {
        load_sql_database();
    }
}

function load_sql_database($file = SqlDatabaseCredentials::STORAGE): void
{
    global $current_sql_database, $previous_sql_database;
    $previous_sql_database = $current_sql_database;
    $current_sql_database = get_keys_from_file(
        "/var/www/.structure/private/" . $file,
        3
    );

    if ($current_sql_database === null) {
        exit("database failure: " . $file);
    } else {
        set_sql_credentials(
            $current_sql_database[0],
            $current_sql_database[1],
            $current_sql_database[2],
            null,
            null,
            null,
            true
        );
    }
}
