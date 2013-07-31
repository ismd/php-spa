<?php
/**
 * Загрузка классов на лету
 * @param string $class Имя класса
 */
function __autoload($class) {
    if (strstr($class, '.')) {
        return;
    }

    // Если класс движка
    if (substr($class, 0, 2) == 'Ps') {
        $file = realpath(dirname(__FILE__)) . '/' . $class . '.php';

        if (is_readable($file)) {
            require_once $file;
            return;
        }
    }

    $explodedClass = explode('_', $class);

    $countExplodedClass = count($explodedClass);
    for ($i = 0; $i < $countExplodedClass - 1; $i++) {
        $explodedClass[$i] = lcfirst($explodedClass[$i]);
    }

    $filename = implode('/', $explodedClass) . '.php';

    // Если класс контроллера
    if (substr($class, -9) == 'Controller') {
        $file = APPLICATION_PATH . '/controllers/' . $filename;

        if (is_readable($file)) {
            require_once $file;
            return;
        }
    }

    // Если класс модели
    $file = APPLICATION_PATH . '/models/' . $filename;

    if (is_readable($file)) {
        require_once $file;
    }
}

// Определяем окружение
switch (PsConfig::getInstance()->config->main->environment) {
    case 'development':
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
        ini_set('display_startup_errors', true);
        break;

    case 'production':
    default:
        error_reporting(E_ALL ^ E_NOTICE);
        ini_set('display_errors', 'Off');
        break;
}

ini_set('log_errors', true);
if (isset(PsConfig::getInstance()->config->main->error_log)) {
    ini_set('error_log', PsConfig::getInstance()->config->main->error_log);
}
