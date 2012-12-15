<?php
/**
 * Инициализируем всё необходимое и запускаем действие
 */

error_reporting(E_ALL ^ E_NOTICE);

session_start();

// Определяем директорию с сайтом
define('SITEPATH', realpath(dirname(__FILE__)) . '/../');

// Инициализируем систему
require SITEPATH . 'lib/ismd-engine/startup.php';

// Registry, в котором будем хранить глобальные значения
$registry = new Registry;

// Читаем конфиг и сохраняем в $registry
try {
    $registry->config = readConfig();
} catch (Exception $e) {
    // Если не удалось прочитать конфиг
    $_GET['route'] = 'index';
}

// Инициализируем собственную реализацию сессий с блэкджеком
$registry->session = new Session;

// Подключаемся к БД и сохраняем соединение в $registry->db
try {
    $registry->db = dbConnect($registry->config->database);
} catch (Exception $e) {
    // Если не удалось подключиться к БД
    $_GET['route'] = 'index';
}

// Загружаем router
$registry->router = new Router(
    $registry,
    (!empty($_GET['route']) ? $_GET['route'] : 'index')
);

// Загружаем класс для работы с шаблонами
$registry->view = new View($registry);

// Выбираем нужный контроллер, определяем действие и выполняем
$registry->router->delegate();

// Отображаем шаблон
$registry->view->render();
