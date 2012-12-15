<?php
/**
 * Роутер
 *
 * @author ismd
 */

class Router {

    protected $_registry;
    protected $_route;
    protected $_rootpath;
    protected $_controller;
    protected $_action;

    // Уровень текущей вложенности роутера
    protected $_level = 0;

    public function __construct($registry, $route, $rootpath = '') {
        $this->_registry = $registry;
        $this->_route    = $route;

        if ($rootpath != '') {
            $this->_level = substr_count($rootpath, '/') + 1;
        }

        // Путь к директории с контроллерами
        $this->_rootpath = SITEPATH . 'application/controllers/' . ($rootpath != '' ? $rootpath . '/' : '');
    }

    /**
     * Подключаем нужный контроллер, модель и выполняем действие
     */
    public function delegate() {
        // Анализируем путь
        $this->getController();

        $controllerFile   = $this->_rootpath . $this->_controller . 'Controller.php';

        // Если недоступен файл контроллера
        if (!is_readable($controllerFile)) {
            header('Location: /');
            die;
        }

        // Подключаем контроллер
        require $controllerFile;

        // Создаём экземпляр контроллера
        $class        = $this->_controller . 'Controller';
        $controller   = new $class($this->_registry);

        $action = $this->_action;

        // Действие доступно?
        if (!is_callable(array($controller, $action))) {
            header('Location: /' . strtolower($this->_controller));
            die;
        }

        // Инициализируем контроллер, если надо
        if (is_callable(array($controller, 'init'))) {
            $controller->init();
        }

        // Выполняем действие
        $controller->$action($this->_registry->args);
    }

    /**
     * Определяем контроллер и действие
     */
    protected function getController() {
        $route = 'index';

        if (!empty($this->_route)) {
            $route = explode('/', $this->_route);

            for ($i = 0; $i < $this->_level; $i++) {
                unset($route[$i]);
            }

            $route = trim(implode('/', $route), '/');
        }

        // Получаем раздельные части
        $parts = explode('/', $route);

        // Делаем первую букву заглавной
        $parts[0] = ucfirst($parts[0]);

        $controller   = $parts[0];
        $action       = (empty($parts[1])) ? 'index' : $parts[1];

        $this->_controller   = $controller;
        $this->_action       = $action;

        // Аргументы
        $args = array();
        $countParts = count($parts);
        for ($i = 2; $i < $countParts; $i++) {
            $args[] = $parts[$i];
        }

        $this->_registry->args = $args;
    }

    /**
     * Возвращает текущее действие
     *
     * @return string
     */
    public function getAction() {
        return $this->_action;
    }

    /**
     * Возвращает запрошенный route
     *
     * @return string
     */
    public function getRoute() {
        return $this->_route;
    }
}
