<?php
/**
 * Класс для работы с шаблонами
 *
 * @author ismd
 */

class View extends ArrayObject {

    protected $_registry;
    
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
     * Необходимость загружать header'ы и footer'ы
     * Для ajax ответов нужно выставлять значение true
     * 
     * @var bool
     */
    protected $_empty = false;

    public function __construct($registry) {
        $this->_registry = $registry;
    }

    /**
     * Отображает страницу
     */
    public function render() {
        // Отправляем заголовок с указанием кодировки
        header('Content-Type: text/html; charset=utf-8');

        $route = $this->_registry->router->getRoute();
        $route = ($route != '') ? explode('/', $route) : array('index');
        $path  = SITEPATH . 'application/views/';

        $route = array_diff($route, array('..'));

        // Подключаем только один файл, если надо
        if ($this->_empty == true) {
            $filename = $path . implode('/', $route) . '.phtml';

            if (is_readable($filename)) {
                require $filename;
            }

            return;
        }

        $headers = array();
        $footers = array();

        $countRoute = count($route);

        // Ищем нужные нам хэдеры и футеры
        for ($i = 0; $i < $countRoute - 1; $i++) {
            if (is_readable($path . 'header.phtml')) {
                $headers[] = $path . 'header.phtml';
            }

            if (is_readable($path . 'footer.phtml')) {
                $footers[] = $path . 'footer.phtml';
            }

            $path .= $route[$i] . '/';

            if (!is_dir($path)) {
                return;
            }
        }

        // Сам файл шаблона и возможно ближайшие хэдер и футер
        if (is_dir($path . $route[$countRoute - 1])) {
            $fileInSubDir = is_readable($path . $route[$countRoute - 1] . '/index.phtml');

            if (is_readable($path . 'header.phtml')) {
                $headers[] = $path . 'header.phtml';
            }

            if ($fileInSubDir && is_readable($path . $route[$countRoute - 1] . '/header.phtml')) {
                $headers[] = $path . $route[$countRoute - 1] . '/header.phtml';
            }

            if ($fileInSubDir) {
                $filepath = $path . $route[$countRoute - 1] . '/index.phtml';
            } elseif ( is_readable($path . $route[$countRoute - 1] . '.phtml') ) {
                $filepath = $path . $route[$countRoute - 1] . '.phtml';
            } else {
                return;
            }

            if (is_readable($path . 'footer.phtml')) {
                $footers[] = $path . 'footer.phtml';
            }

            if ($fileInSubDir && is_readable($path . $route[$countRoute - 1] . '/footer.phtml')) {
                $footers[] = $path . $route[$countRoute - 1] . '/footer.phtml';
            }
        } else {
            if (is_readable($path . 'header.phtml')) {
                $headers[] = $path . 'header.phtml';
            }

            if (is_readable($path . $route[$countRoute - 1] . '.phtml')) {
                $filepath = $path . $route[$countRoute - 1] . '.phtml';
            } else {
                return;
            }

            if (is_readable($path . 'footer.phtml')) {
                $footers[] = $path . 'footer.phtml';
            }
        }

        // Подключаем все нужные хэдеры
        foreach ($headers as $header) {
            require $header;
        }

        // Подключаем сам файл шаблона
        if (is_readable($filepath)) {
            require $filepath;
        }

        // Подключаем все нужные футеры
        $footers = array_reverse($footers);
        foreach ($footers as $footer) {
            require $footer;
        }
    }

    /**
     * Переданная ссылка будет вставлена в качестве ссылки на javascript-файл
     * Может быть передан массив ссылок
     *
     * @param string|array $link Ссылка или массив ссылок на javascript-файлы
     * @return View
     */
    public function js($link) {
        if (is_array($link)) {
            $this->_js = array_merge($this->_js, $link);
        } else {
            $this->_js[] = $link;
        }

        return $this;
    }

    /**
     * Переданная ссылка будет вставлена в качестве ссылки на css-файл
     * Может быть передан массив ссылок
     *
     * @param string|array $link Ссылка или массив ссылок на css-файлы
     * @return View
     */
    public function css($link) {
        if (is_array($link)) {
            $this->_css = array_merge($this->_css, $link);
        } else {
            $this->_css[] = $link;
        }

        return $this;
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
     * Устаналивает необходимость загружать header'ы и footer'ы
     *
     * @param bool
     * @return View
     */
    public function setEmpty($value) {
        $this->_empty = (bool)$value;
        return $this;
    }

    public function getEmpty() {
        return $this->_empty;
    }
}
