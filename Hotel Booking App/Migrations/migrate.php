<?php

// connect to the database
$conn = mysqli_connect('localhost', 'root', '', 'bookingapp');

// make sure that warning and not exception is thrown. If MYSQLI_REPORT_STRICT is enabled then we need to surround mysqli_query call with try/catch block
mysqli_report(MYSQLI_REPORT_ERROR);

// fetch all rows from migrations table
$migrationsQuery = mysqli_query($conn, 'select * from migrations');

// initialize an empty array where we will store all processed migrations. Alternative syntax is $processedMigrations = array();
$processedMigrations = [];

// if migrationsQuery variable holds false value it means query failed. We can assume it failed due to missing table
if (!$migrationsQuery) {
    $migrationsTableDdl = 'CREATE TABLE IF NOT EXISTS `migrations` (
`id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`));';

    // here we execute the DDL statement to create migrations table
    $query = mysqli_query($conn, $migrationsTableDdl);
} else {
    // iterate over result set
    while ($row = mysqli_fetch_assoc($migrationsQuery)) {
        // append the item at the end of the array. Alternative syntax array_push($migrations, $row['name'])
        $processedMigrations[] = $row['name'];
    }
}

// initialize an empty array where we will store all available migrations. Alternative syntax is $migrations = array();
$migrations = [];

$path = './migrations';

if (is_dir($path) && $handle = opendir($path)) {
    while (false !== ($entry = readdir($handle))) {
        if (is_file($path . '/' . $entry)) {
            $migrations[] = $entry;
        }
    }
}

// sort array in natural ascending order. The original array is changed because the natsort function receives a reference.
natsort($migrations);

// array_diff will return us all migrations that are not in the second array, that is, that are not processed.
$migrationsToRun = array_diff($migrations, $processedMigrations);

// iterate over array
foreach ($migrationsToRun as $migrationToRun) {
    // file_get_contents will return the content of the file
    $migrationDdl = file_get_contents($path . '/' . $migrationToRun);

    // we execute the ddl
    $result = mysqli_query($conn, $migrationDdl);

    if (!$result) {
        // if a single migration failed we want to stop the further processing
        echo $migrationToRun . ' migration failed';
        break;
    } else {
        // if successful, store it in the database
        mysqli_query($conn, "insert into migrations (name) values ('$migrationToRun')");
    }
}
