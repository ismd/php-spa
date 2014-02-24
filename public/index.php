<?php
// Определяем директорию с исходниками
define('APPLICATION_PATH', realpath(dirname(__FILE__)) . '/../application');

set_include_path(implode(':', [
    realpath(APPLICATION_PATH . '/../lib'),
    get_include_path(),
]));

// Инициализируем систему
require 'php-spa/startup.php';

// PsRegistry, в котором будем хранить глобальные значения
$registry = new PsRegistry;

// Устанавливаем временную зону сервера
$config = PsConfig::getInstance();
if (!is_null($config->timezone->server)) {
    date_default_timezone_set($config->timezone->server);
}

// Загружаем router
$registry->router = new PsRouter($registry, isset($_GET['route']) ? $_GET['route'] : '');

// Загружаем класс для работы с шаблонами
$registry->view = new PsView($registry);

// Выбираем нужный контроллер, определяем действие и выполняем
$registry->router->delegate();

// Отображаем вывод
$registry->view->render();
