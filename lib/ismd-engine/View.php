<?php
/**
 * Класс для работы с шаблонами
 *
 * Установка заголовка страницы `test'
 * $view->setTitle('test')
 *
 * Будет подключен layout empty.phtml
 * $view->setLayout('empty')
 *
 * Будет подключен js-файл test.js
 * $view->appendJs('test')
 * Вторым параметром возможно передавать дополнительные атрибуты
 * $view->appendJs('test', array('data-main' => 'js/main'))
 *
 * Будут подключены css-файлы test.css и test1.css
 * $view->appendCss(array('test', 'test1'))
 *
 * Передача в шаблон переменной test со значением 'test'
 * $view->test = 'test'
 *
 * Возвращает подключаемый шаблон. Необходимо использовать в layout'е
 * $this->content()
 *
 * Возвращает заголовок страницы
 * $this->getTitle()
 *
 * Возвращает имя layout'а
 * $this->getLayout()
 *
 * Возвращает массив js-файлов, которые необходимо подключить
 * Вызов только из шаблонов
 * $this->getJs()
 *
 * Возвращает массив css-файлов, которые необходимо подключить
 * Вызов только из шаблонов
 * $this->getCss()
 *
 * Обращаться к переменным в шаблоне необходимо следующим образом
 * $this->test
 *
 * @author ismd
 */

class View {

    protected $_registry;

    /**
     * Переменные шаблона
     * @var array
     */
    protected $_data = array();

    /**
     * Массив подключаемых js-файлов
     * @var array
     */
    protected $_js  = array();

    /**
     * Массив подключаемых css-файлов
     * @var array
     */
    protected $_css = array();

    /**
     * Заголовок страницы
     * @var string
     */
    protected $_title = '';

    /**
     * Layout для отображения
     * @var string
     */
    protected $_layout = 'default';

    public function __construct($registry) {
        $this->_registry = $registry;
    }

    public function __set($name, $value) {
        $this->_data[$name] = $value;
        return $this;
    }

    public function __get($name) {
        if (false == isset($this->_data[$name])) {
            return null;
        }

        return $this->_data[$name];
    }

    public function __isset($name) {
        return isset($this->_data[$name]);
    }

    public function __unset($name) {
        if (false == isset($this->_data[$name])) {
            return;
        }

        unset($this->_data[$name]);
    }

    /**
     * Отображает страницу
     */
    public function render() {
        // Отправляем заголовок с указанием кодировки
        header('Content-Type: text/html; charset=utf-8');

        $filename = SITEPATH . 'application/layouts/' . $this->_layout . '.phtml';

        if (false == is_readable($filename)) {
            throw new Exception('Cannot read layout file');
        }

        require $filename;
    }

    /**
     * Возвращает содержимое запрошенной страницы
     * @return string
     */
    protected function content() {
        $router = $this->_registry->router;

        // Путь к директории с шаблонами
        $viewsPath  = SITEPATH . 'application/views/';

        // Путь к файлу шаблона
        $filename = $viewsPath . $router->getController()
            . '/' . $router->getAction() . '.phtml';

        if (is_readable($filename)) {
            require $filename;
        }
    }

    /**
     * Переданная ссылка будет вставлена в качестве ссылки на javascript-файл
     *
     * @param string $link Ссылка на javascript-файл
     * @param array $attributes Дополнительные атрибуты
     * @return View
     */
    public function appendJs($link, $attributes = array()) {
        $this->_js[] = array(
            'link'       => $link,
            'attributes' => $attributes,
        );

        return $this;
    }

    /**
     * Возвращает массив js-файлов, которые будут подключены
     * @return array
     */
    protected function getJs() {
        return $this->_js;
    }

    /**
     * Переданная ссылка будет вставлена в качестве ссылки на css-файл
     * Может быть передан массив ссылок
     *
     * @param string|array $link Ссылка или массив ссылок на css-файлы
     * @return View
     */
    public function appendCss($link) {
        if (is_array($link)) {
            $this->_css = array_merge($this->_css, $link);
        } else {
            $this->_css[] = $link;
        }

        return $this;
    }

    /**
     * Возвращает массив css-файлов, которые будут подключены
     * @return array
     */
    protected function getCss() {
        return $this->_css;
    }

    /**
     * Устанавливает заголовок страницы
     *
     * @param string
     * @return View
     */
    public function setTitle($value) {
        $this->_title = '::' . (string)$value;
        return $this;
    }

    public function getTitle() {
        return $this->_title;
    }

    /**
     * Устаналивает Layout
     *
     * @param string
     * @return View
     */
    public function setLayout($value) {
        $this->_layout = (string)$value;
        return $this;
    }

    public function getLayout() {
        return $this->_layout;
    }
}
