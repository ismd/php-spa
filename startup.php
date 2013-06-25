<?php
/**
 * Загрузка классов на лету
 * @param string $class Имя класса
 */
function __autoload($class) {
    if (strstr($class, '.')) {
        return;
    }

    $filename = $class . '.php';

    // Если класс движка
    if (substr($class, 0, 2) == 'Ps') {
        $file = realpath(dirname(__FILE__)) . '/' . $filename;

        if (is_readable($file)) {
            require_once $file;
            return;
        }
    }

    // Если класс контроллера
    if (substr($class, -9) == 'Controller') {
        $file = SITEPATH . 'application/controllers/' . $filename;

        if (is_readable($file)) {
            require_once $file;
            return;
        }
    }

    // Если класс абстрактной модели
    if (substr($class, 0, 8) == 'Abstract') {
        $file = SITEPATH . 'application/models/abstract/' . $filename;

        if (is_readable($file)) {
            require_once $file;
            return;
        }
    }

    // Если класс модели
    $file = SITEPATH . 'application/models/' . $filename;
    
    if (is_readable($file)) {
        require_once $file;
    }
}
