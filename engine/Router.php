<?php
/**
 * Роутер
 */

class Router {

    private $registry;
    private $path;
    private $controller;
    private $action;

    public function __construct($registry) {
        $this->registry = $registry;

        // Путь к директории с контроллерами и моделями
        $path = SITEPATH . 'engine' . DIRSEP . 'modules' . DIRSEP;

        if (!is_dir($path))
            die("Не могу найти директорию с движком игры.");

        $this->path = $path;
    }

    /**
     * Подключаем нужный контроллер, модель и выполняем действие
     */
    public function delegate() {
        // Анализируем путь
        $this->getController();

        $controllerFile = $this->path . $this->controller . 'Controller.php';
        $modelFile = $this->path . $this->controller . 'Model.php';

        // Если не доступен файл контроллера
        if (!file_exists($controllerFile) || !is_readable($controllerFile)) {
            header('Location: /');
            die;
        }

        // Подключаем контроллер
        require $controllerFile;

        // Подключаем модель, если есть
        if (file_exists($modelFile) && is_readable($modelFile)) {
            require $modelFile;

            $class = $this->controller . 'Model';
            $model = new $class($this->registry);
        }

        // Создаём экземпляр контроллера
        $class = $this->controller . 'Controller';

        if (isset($model))
            $controller = new $class($this->registry, $model);
        else
            $controller = new $class($this->registry);

        $action = $this->action;

        // Действие доступно?
        if (!is_callable(array($controller, $action)))
            $action = 'index';

        // Выполняем действие
        $controller->$action();

        // Определяем массив последовательных действий
        $action = array();
        $action[] = $this->action;

        if (!empty($this->registry['subaction']))
            $action[] = $this->registry['subaction'];
        
        // Выводим шаблон
        $this->registry['template']->show(strtolower($this->controller), $action);
    }

    /**
     * Определяем контроллер и действие
     */
    private function getController() {
        $route = (empty($_GET['route'])) ? 'index' : $_GET['route'];

        // Получаем раздельные части
        $route = trim($route, '/');
        $parts = explode('/', $route);

        // Делаем первую букву заглавной
        $parts[0][0] = strtoupper($parts[0][0]);

        $controller = $parts[0];
        $action = (empty($parts[1])) ? 'index' : $parts[1];

        $this->controller = $controller;
        $this->action = $action;

        // Аргументы
        $args = array();
        $countParts = count($parts);
        for ($i = 2; $i < $countParts; $i++) {
            $args[] = $parts[$i];
        }

        $this->registry['args'] = $args;
    }

    public function action() {
        return $this->action;
    }
}
?>
