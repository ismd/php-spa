<?php
error_reporting(E_ALL ^ E_NOTICE);

// Определяем директорию с сайтом
define('SITEPATH', realpath(dirname(__FILE__)) . '/../');

// Инициализируем систему
require SITEPATH . 'lib/php-spa/startup.php';

// PsRegistry, в котором будем хранить глобальные значения
$registry = new PsRegistry;

// Читаем конфиг и сохраняем в $registry
$registry->config = PsConfig::getInstance(SITEPATH)->getConfig();

// Устанавливаем временную зону сервера
date_default_timezone_set($registry->config->timezone->server);

// Загружаем router
$registry->router = new PsRouter($registry, $_GET['route']);

// Загружаем класс для работы с шаблонами
$registry->view = new PsView($registry);

// Выбираем нужный контроллер, определяем действие и выполняем
$registry->router->delegate();

// Отображаем вывод
$registry->view->render();
