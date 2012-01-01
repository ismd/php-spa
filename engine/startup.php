<?php
/**
 * Загрузка классов "на лету"
 */
function __autoload($className) {
    // TODO: надо лучше сделать определение имени файла (с проверкой)
    $filename = $className . '.php';

    $file = SITEPATH . 'engine' . DIRSEP . $filename;

    if (file_exists($file)) {
        require $file;
        return;
    }

    $file = SITEPATH . 'engine' . DIRSEP . 'modules' . $filename;

    if (file_exists($file)) {
        require $file;
        return;
    }

    return false;
}
?>
