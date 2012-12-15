<?php
/**
 * Загрузка классов "на лету"
 *
 * @author ismd
 */
function __autoload($className) {
    if (strstr($className, '.')) {
        return;
    }

    $filename = $className . '.php';

    // Класс движка
    $file = realpath(dirname(__FILE__)) . '/' . $filename;
    if (is_readable($file)) {
        require_once $file;
    }

    // Класс контроллера
    if (substr($className, -9) == 'Controller') {
        $file = SITEPATH . 'application/controllers/' . $filename;
        if (is_readable($file)) {
            require_once $file;
        }
    }

    // Класс модели
    $file = SITEPATH . 'application/models/' . $filename;
    if (is_readable($file)) {
        require_once $file;
    }

    // Класс абстрактной модели
    if (substr($className, 0, 8) == 'Abstract') {
        $file = SITEPATH . 'application/models/abstract/' . $filename;
        if (is_readable($file)) {
            require_once $file;
        }
    }
}

/**
 * Подключается к БД
 *
 * @param object $config Данные для подключения
 * @throws Can't connect to database
 * @return mysqli
 */
function dbConnect($config) {
    $mysqli = new mysqli(
        $config->host,
        $config->username,
        $config->password,
        $config->dbname
    );

    if ($mysqli->connect_error) {
        throw new Exception("Can't connect to database");
    }

    return $mysqli;
}

/**
 * Читает конфиг
 *
 * @throws Can't read config file
 * @return array
 */
function readConfig() {
    $configFilename = SITEPATH . 'application/configs/application.ini';

    if (!is_readable($configFilename)) {
        throw new Exception("Can't read config file");
    }

    $config = parse_ini_file($configFilename, true);
    
    foreach ($config as $i => $c) {
        $config[$i] = (object)$c;
    }

    return (object)$config;
}
