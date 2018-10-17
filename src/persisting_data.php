<?php

function persistData($results)
{
    $db_mysql = new mysqli($_ENV['MYSQL_HOST'], $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD'], $_ENV['MYSQL_DATABASE'], $_ENV['MYSQL_PORT']);

    if (mysqli_connect_errno()) {
        printf("Не удалось подключиться: %s\n", mysqli_connect_error());
        exit();
    }

    foreach ($results as $table_name => $row) {
        foreach ($row as $column_data) {
            $sql = "INSERT INTO {$table_name} VALUES ";
            $sql .= '(' . implode(',', $column_data) . ')';

//            echo $sql . "\n";
//
//            if ($db_mysql->query($sql) !== TRUE) {
//                echo "Error: {$db_mysql->error}\n";
//            }
            $db_mysql->query($sql);
        }
    }

    $db_mysql->close();
}