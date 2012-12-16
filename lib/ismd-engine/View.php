<?php
/**
 * Класс для работы с шаблонами
 * 
 * Использование:
 * - $view->title  = 'test'        -- Устанавливает заголовок страницы `test'
 * - $view->layout = 'empty'       -- Будет подключен layout empty.phtml
 * - $view->js     = array('test') -- Будет подключен js-файл с именем test.js
 * - $view->css    = array('test') -- Будет подключен css-файл с именем test.css
 * - $view->test   = 'test'        -- Передача в шаблон переменной test со значением 'test'
 * - $this->content                -- Возвращает подключаемый шаблон. Необходимо использовать в layout'е
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
        switch ($name) {
            case 'title':
                return $this->setTitle($value);
                break;
            
            case 'layout':
                return $this->setLayout($value);
                break;
            
            case 'js':
                return $this->setJs($value);
                break;
            
            case 'css':
                return $this->setCss($value);
                break;
            
            default:
                break;
        }
        
        $this->_data[$name] = $value;
        return $this;
    }
    
    public function __get($name) {
        switch ($name) {
            case 'title':
                return $this->getTitle();
                break;
            
            case 'layout':
                return $this->getLayout();
                break;
            
            case 'js':
                return $this->getJs();
                break;
            
            case 'css':
                return $this->getCss();
                break;
            
            case 'content':
                return $this->getContent();
                break;
            
            default:
                break;
        }
        
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
        require SITEPATH . 'application/layouts/' . $this->_layout . '.phtml';
    }

    /**
     * Возвращает содержимое запрошенной страницы
     * @return string
     */
    protected function getContent() {
        $router = $this->_registry->router;
        
        // Путь к директории с шаблонами
        $viewsPath  = SITEPATH . 'application/views/';

        // Путь к файлу шаблона
        $filename = $viewsPath . $router->controller . '/' . $router->action . '.phtml';

        if (is_readable($filename)) {
            require $filename;
        }
    }

    /**
     * Переданная ссылка будет вставлена в качестве ссылки на javascript-файл
     * Может быть передан массив ссылок
     *
     * @param string|array $link Ссылка или массив ссылок на javascript-файлы
     * @return View
     */
    public function setJs($link) {
        if (is_array($link)) {
            $this->_js = array_merge($this->_js, $link);
        } else {
            $this->_js[] = $link;
        }

        return $this;
    }
    
    /**
     * Возвращает массив js-файлов, которые будут подключены
     * @return array
     */
    public function getJs() {
        return $this->_js;
    }

    /**
     * Переданная ссылка будет вставлена в качестве ссылки на css-файл
     * Может быть передан массив ссылок
     *
     * @param string|array $link Ссылка или массив ссылок на css-файлы
     * @return View
     */
    public function setCss($link) {
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
    public function getCss() {
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
