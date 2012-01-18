<?php
/**
 * Инициализируем всё необходимое и запускаем действие
 */

// Отправляем заголовок с указанием кодировки
header('Content-Type: text/html; charset=utf-8');

error_reporting (E_ALL);

session_start();

// Определяем директорию с сайтом
define ('SITEPATH', realpath(dirname(__FILE__)) . '/');

// Инициализируем систему
require SITEPATH . 'engine/startup.php';

// Registry, в котором будем хранить глобальные значения
$registry = new Registry;

// Загружаем router
$router = new Router($registry);
$registry['router'] = $router;

// Загружаем класс для работы с шаблонами
$template = new Template($registry);
$registry['template'] = $template;

// Выбираем нужный контроллер, определяем действие и выполняем
$router->delegate();
$router->showTemplate();
?>
