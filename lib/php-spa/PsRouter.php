<?php
/**
 * Роутер
 *
 * Запуск:
 * $router->delegate()
 *
 * Получение имени контроллера:
 * $router->getController()
 *
 * Получение имени действия:
 * $router->getAction()
 *
 * Получение переданных аргументов
 * $router->getArgs()
 *
 * @author ismd
 */

class PsRouter {

    const INDEX_REQUEST   = 1;
    const PARTIAL_REQUEST = 2;
    const ACTION_REQUEST  = 3;

    protected $_registry;
    protected $_route;

    /**
     * Возможные префиксы
     * @var string[]
     */
    protected $_prefixes;

    /**
     * Префикс запроса
     * @var string
     */
    protected $_prefix;

    /**
     * Контроллер
     * @var string
     */
    protected $_controller;

    /**
     * Действие
     * @var string
     */
    protected $_action;

    /**
     * Аргументы запроса
     * Пример: <префикс>/<контроллер>/<действие>/<арг.1>/<арг.2>/...
     * @var mixed[]
     */
    protected $_args = array();

    public function __construct($registry, $route) {
        $this->_registry = $registry;
        $this->_route    = $route;

        $this->_prefixes = $this->_registry->config->url_prefixes;
    }

    /**
     * Подключаем нужный контроллер, модель и выполняем действие
     */
    public function delegate() {
        // Анализируем путь
        $this->parseRoute();

        // Имя класса контроллера
        $controllerName = ucfirst($this->_controller) . 'Controller';

        // Путь к директории с контроллерами
        $controllersPath = SITEPATH . 'application/controllers/';

        // Путь к контроллеру
        $controllerFile = $controllersPath . $controllerName . '.php';

        // Если недоступен файл контроллера
        if (!is_readable($controllerFile)) {
            die;
        }

        // Подключаем контроллер
        require_once $controllerFile;

        // Создаём экземпляр контроллера
        $controller = new $controllerName($this->_registry);

        // Определяем вызываемый метод
        $action = $this->_action;

        $requestType = $this->getRequestType();

        switch ($requestType) {
            case self::PARTIAL_REQUEST:
                $action .= 'Partial';
                break;

            case self::ACTION_REQUEST:
                $action .= 'Action';
                break;
        }

        // Если действие недоступно
        if (!is_callable(array($controller, $action))) {
            die;
        }

        // Инициализируем контроллер, если надо
        if (is_callable(array($controller, 'init'))) {
            $controller->init();
        }

        // Выполняем действие
        $controller->$action();
    }

    /**
     * Определяет контроллер, действие и аргументы
     * Устанавливает свойства _controller, _action и _args
     */
    protected function parseRoute() {
        $route = explode('/', $this->_route);

        // Префикс
        $this->_prefix = $route[0];

        if (!in_array($this->_prefix, (array)$this->_prefixes)) {
            $this->_controller = 'index';
            $this->_action     = 'index';
            return;
        }

        // Контроллер
        $this->_controller = strtolower($route[1]);

        // Действие
        if (count($route) > 2) {
            $this->_action = strtolower($route[2]);
        } else {
            $this->_action = 'index';
        }

        // Аргументы
        $this->_args = array_slice($route, 3);
    }

    /**
     * Возвращает контроллер
     * @return string
     */
    public function getController() {
        return $this->_controller;
    }

    /**
     * Возвращает действие
     * @return string
     */
    public function getAction() {
        return $this->_action;
    }

    /**
     * Возвращает аргументы, переданные в url
     * @return mixed[]
     */
    public function getArgs() {
        return $this->_args;
    }

    /**
     * Возвращает тип запрошенной страницы
     * Возможные варианты:
     * - PsRouter::INDEX_REQUEST
     * - PsRouter::PARTIAL_REQUEST
     * - PsRouter::ACTION_REQUEST
     * @return int
     */
    public function getRequestType() {
        if ($this->_prefix == $this->_prefixes->partial) {
            return self::PARTIAL_REQUEST;
        } elseif ($this->_prefix == $this->_prefixes->action) {
            return self::ACTION_REQUEST;
        } else {
            return self::INDEX_REQUEST;
        }
    }
}
