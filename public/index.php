<?php
// Определяем директорию с исходниками
define('APPLICATION_PATH', realpath(dirname(__FILE__)) . '/../application');

set_include_path(implode(':', [
    realpath(APPLICATION_PATH . '/../lib'),
    get_include_path(),
]));

// Инициализируем систему
require 'php-spa/startup.php';
