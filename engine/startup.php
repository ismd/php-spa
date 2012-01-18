<?php
/**
 * Загрузка классов "на лету"
 */
function __autoload($className) {
    if (strstr($className, '.'))
        return false;

    $filename = $className . '.php';

    $file = SITEPATH . 'engine/' . $filename;
    if (is_readable($file)) {
        require $file;
        return;
    }

    return false;
}

// Подключаемся к базе данных
if (!(mysql_connect('localhost', 'root', '123') && mysql_select_db('game')))
    $registry['db_error'] = true;
?>
