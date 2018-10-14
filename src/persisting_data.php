<?php

function persistData($results)
{
    $db_mysql = new mysqli('127.0.0.1', 'root', 'root', 'url_shortener', 3306);

    if (mysqli_connect_errno()) {
        printf("Не удалось подключиться: %s\n", mysqli_connect_error());
        exit();
    }

    foreach ($results as $table_name => $row) {
        foreach ($row as $column_data) {
            $sql = "INSERT IGNORE INTO {$table_name} VALUES ";
            $sql .= '(' . implode(',', $column_data) . ')';

//            echo $sql . "\n";

            if ($db_mysql->query($sql) !== TRUE) {
                echo "Error: {$db_mysql->error}\n";
            }
        }
    }

    $db_mysql->close();
}