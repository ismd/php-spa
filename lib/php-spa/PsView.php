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
class PsView extends PsObject {

    protected $_registry;

    /**
     * Флаг вывода
     * @var boolean
     */
    private $_rendered = false;

    /**
     * JSON-данные для вывода при запросе действия
     * @var mixed[]
     */
    protected $_json = [];

    /**
     * Имя шаблона
     * @var string
     */
    protected $_partial;

    public function __construct($registry) {
        $this->_registry = $registry;
    }

    /**
     * Отображает страницу
     * @param string $partial Если передан параметр, то выводим заданный шаблон
     * @throws ActionFinishedException
     * @throws Exception
     */
    public function render($partial = null) {
        if ($this->_rendered) {
            return;
        }

        $this->_partial = $partial;
        header('Content-Type: text/html; charset=utf-8');

        if (is_null($partial)) {
            switch ($this->_registry->router->getRequestType()) {
                case PsRouter::PARTIAL_REQUEST:
                    $this->content();
                    return;

                case PsRouter::ACTION_REQUEST:
                    echo json_encode($this->_json);
                    return;

                case PsRouter::NON_SPA:
                    if (!empty($this->_json)) {
                        echo json_encode($this->_json);
                        return;
                    }
            }

            if (!empty($this->_json)) {
                echo json_encode($this->_json);
                return;
            }
        }

        // Отображаем главную страницу
        $filename = APPLICATION_PATH . '/views/layout.phtml';

        if (!is_readable($filename)) {
            throw new Exception('Layout not found');
        }

        require $filename;
        $this->_rendered = true;
        throw new ActionFinishedException;
    }

    /**
     * Выводит шаблон
     * @throws Exception
     */
    protected function content() {
        // Путь к директории с шаблонами
        $viewsPath = APPLICATION_PATH . '/views/';

        if (!is_null($this->_partial)) {
            $filename = $viewsPath . $this->_partial . '.phtml';

            if (is_readable($filename)) {
                require $filename;
                return;
            } else {
                throw new Exception('Partial not found');
            }
        }

        $router = $this->_registry->router;

        // Путь к файлу шаблона
        $filename = $viewsPath . lcfirst($router->getController())
            . '/' . $router->getAction() . '.phtml';

        if (!is_readable($filename)) {
            throw new Exception('Partial not found');
        }

        require $filename;
    }

    /**
     * Передача json-данных для вывода в шаблон
     * Можно использовать только при запросе действия
     * @param mixed[] $json
     * @throws ActionFinishedException
     */
    public function json($value) {
        $this->_json = (array)$value;
        throw new ActionFinishedException;
    }
}
