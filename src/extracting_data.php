<?php

function extractData()
{
    $db_sqlite = new \SQLite3($_ENV['SQLITE_FILE'], SQLITE3_OPEN_READWRITE);

    $sql = 'SELECT * FROM main_table';

    $query = $db_sqlite->query($sql);

    $results = [];
    $i = 0;
    while ($res = $query->fetchArray(SQLITE3_ASSOC)) {
        foreach ($res as $key => $value) {
            [$table_name, $column_name] = explode('_', $key, 2);
            if (is_null($value)) {
                $value = 'null';
            } elseif (is_string($value)) {
                $value = '\'' . $value . '\'';
            }
            $results[$table_name][$i][] = $value;
        }
        $i++;
    }

// сортируем, чтобы ограничния не повлияли при добавлении в бд
    return [
        'user' => $results['user'],
        'url' => $results['url'],
        'click' => $results['click'],
        'promocode' => $results['promocode'],
        'redeem' => $results['redeem']
    ];
}