<?php
/**
 * Инициализируем всё необходимое и запускаем действие
 */

// Отправляем заголовок с указанием кодировки
header('Content-Type: text/html; charset=utf-8');

error_reporting (E_ALL);

session_start();

// delete !!!
//unset($_SESSION['current_game']);

define ('DIRSEP', DIRECTORY_SEPARATOR);

// Определяем директорию с сайтом
define ('SITEPATH', realpath(dirname(__FILE__)) . DIRSEP);

// Инициализируем систему
require SITEPATH . 'engine' . DIRSEP . 'startup.php';

// Registry, в котором будем хранить глобальные значения
$registry = new Registry;

// Подключаемся к базе данных
// TODO: перенести куда-нибудь отсюда подключение
@mysql_connect('localhost', 'root', '123') && mysql_select_db('ismd_forsbs') || $registry['db_error'] = true;

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
