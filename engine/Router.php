<?php
/**
 * Роутер
 */

class Router {

    private $registry;
    private $rootpath;
    private $controller;
    private $action;

    // Уровень текущей вложенности роутера
    private $level = 0;

    public function __construct($registry, $rootpath = '') {
        $this->registry = $registry;

        if ($rootpath != '')
            $this->level = substr_count($rootpath, '/') + 1;

        // Путь к директории с контроллерами и моделями
        $path = SITEPATH . 'application' . ($rootpath != '' ? '/' . $rootpath : '');

        if (!is_dir($path))
            die("Не могу найти директорию с движком игры.");

        $this->rootpath = $path;
    }

    /**
     * Подключаем нужный контроллер, модель и выполняем действие
     */
    public function delegate() {
        // Анализируем путь
        $this->getController();

        $controllerFile = $this->rootpath . '/' . $this->controller . 'Controller.php';
        $modelFile = $this->rootpath . '/' . $this->controller . 'Model.php';

        // Если не доступен файл контроллера
        if (!is_readable($controllerFile)) {
            header('Location: /');
            die;
        }

        // Подключаем контроллер
        require $controllerFile;

        // Подключаем модель, если есть
        if (is_readable($modelFile)) {
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

        // Подключаем нужные контроллеру модели
        $requiredModels = $controller->requiredModels();
        foreach ($requiredModels as $status => $reqModel) {
            $reqModel = explode('/', $reqModel);
            $countReqModel = count($reqModel);
            $reqModel[$countReqModel - 1] = ucfirst($reqModel[$countReqModel - 1]);
            $reqModel = implode('/', $reqModel);

            $filename = SITEPATH . 'engine/modules/' . $reqModel . 'Model.php';

            if (is_readable($filename)) {
                require_once $filename;

                if ($status == 'main') {
                    $class = $reqModel . 'Model';
                    $controller->setModel(new $class($this->registry));
                }
            }
        }

        $action = $this->action;

        // Действие доступно?
        if (!is_callable(array($controller, $action))) {
            header('Location: /' . strtolower($this->controller));
            die;
        }

        // Выполняем действие
        $controller->$action($this->registry['args']);
    }

    public function showTemplate() {
        $this->registry['template']->show();
    }

    /**
     * Определяем контроллер и действие
     */
    private function getController() {
        if (empty($_GET['route']))
            $route = 'index';
        else {
            $route = explode('/', $_GET['route']);

            for ($i = 0; $i < $this->level; $i++)
                unset($route[$i]);

            $route = trim(implode('/', $route), '/');
        }

        // Получаем раздельные части
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
