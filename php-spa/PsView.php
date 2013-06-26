<?php
/**
 * Класс для работы с шаблонами
 *
 * Передача в шаблон переменной test со значением 'test':
 * $view->test = 'test'
 *
 * Обращаться к переменным в шаблоне следующим образом:
 * $this->test
 *
 * @author ismd
 */

class PsView {

    protected $_registry;

    /**
     * Переменные шаблона
     * @var mixed[]
     */
    protected $_data = array();

    /**
     * JSON-данные для вывода при запросе действия
     * @var mixed[]
     */
    protected $_json = array();

    public function __construct($registry) {
        $this->_registry = $registry;
    }

    public function __set($name, $value) {
        $this->_data[$name] = $value;
        return $this;
    }

    public function __get($name) {
        return $this->_data[$name];
    }

    public function __isset($name) {
        return isset($this->_data[$name]);
    }

    public function __unset($name) {
        if (!isset($this->_data[$name])) {
            return;
        }

        unset($this->_data[$name]);
    }

    /**
     * Отображает страницу
     */
    public function render() {
        header('Content-Type: text/html; charset=utf-8');

        $router = $this->_registry->router;

        switch ($router->getRequestType()) {
            case PsRouter::PARTIAL_REQUEST:
                $this->renderPartial();
                break;

            case PsRouter::ACTION_REQUEST:
                $this->renderJson();
                break;

            default:
                // Отображаем главную страницу
                $filename = APPLICATION_PATH . '/views/layout.phtml';

                if (is_readable($filename)) {
                    require $filename;
                }
                break;
        }
    }

    /**
     * Выводит содержимое запрошенной страницы
     */
    protected function renderPartial() {
        $router = $this->_registry->router;

        // Путь к директории с шаблонами
        $viewsPath  = APPLICATION_PATH . '/views/';

        // Путь к файлу шаблона
        $filename = $viewsPath . $router->getController()
            . '/' . $router->getAction() . '.phtml';

        if (is_readable($filename)) {
            require $filename;
        }
    }

    /**
     * Отображает json-данные
     */
    protected function renderJson() {
        echo json_encode($this->_json);
    }

    /**
     * Передача json-данных для вывода в шаблон
     * Можно использовать только при запросе действия
     * @param mixed[] $json
     * @return PsView
     */
    public function json($value) {
        $this->_json = (array)$value;
        return $this;
    }
}
