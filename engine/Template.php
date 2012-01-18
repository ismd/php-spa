<?php
/**
 * Класс для работы с шаблонами
 */

class Template implements ArrayAccess {

    private $registry;
    private $data = array();
    private $ownHeaderFooter = false;
    private $js = array();
    private $css = array();
    private $title = '';

    public function __construct($registry) {
        $this->registry = $registry;
    }

    /**
     * Устанавливаем значение переменной
     *
     * @param string $varname - имя
     * @param mixed $value - значение
     * @param boolean $overwrite - переписать значение
     */
    public function set($key, $value, $overwrite=false) {
        if (isset($this->data[$key]) && !$overwrite) {
            trigger_error('Unable to set var `' . $key . '`. Already set, and overwrite not allowed.', E_USER_NOTICE);

            return false;
        }

        $this->data[$key] = $value;
    }

    public function get($key) {
        if (!isset($this->data[$key]))
            return null;

        return $this->data[$key];
    }

    /**
     * Удалить переменную
     *
     * @param string $varname - имя
     */
    public function remove($key) {
        unset($this->data[$key]);
    }

    /**
     * Методы для ArrayAccess
     */
    function offsetExists($offset) {
        return isset($this->data[$offset]);
    }

    function offsetGet($offset) {
        return $this->get($offset);
    }

    function offsetSet($offset, $value) {
        $this->set($offset, $value);
    }

    function offsetUnset($offset) {
        $this->remove[$offset];
    }

    /**
     * Загружаем нужный шаблон
     */
    public function show() {
        // Чтобы было удобней обращаться к переменным шаблона из самого шаблона
        $data = $this->data;

        $getRoute = (empty($_GET['route'])) ? 'index' : trim($_GET['route'], '/');

        if ($this->ownHeaderFooter) {
            $path = SITEPATH . 'templates/' .  $getRoute . '/';
            $filename = substr($path, 0, -1) . '.phtml';
            $path1 = strrev(strstr(strrev($filename), '/'));

            if (is_readable($filename)) {
                if (is_readable($path1 . 'header.phtml'))
                    require $path1 . 'header.phtml';

                require $filename;

                if (is_readable($path1 . 'footer.phtml'))
                    require $path1 . 'footer.phtml';
            }

            if (is_readable($path . 'header.phtml'))
                require $path . 'header.phtml';

            if (is_readable($path . 'index.phtml'))
                require $path . 'index.phtml';

            if (is_readable($path . 'footer.phtml'))
                require $path . 'footer.phtml';

            return;
        }

        $route = $getRoute . '/';
        $path = SITEPATH . 'templates/';
        $countRoute = substr_count($route, '/') + 1;

        // Подключаем последовательно header'ы
        for ($i = 0; $i < $countRoute; $i++) {
            if (is_readable($path . 'header.phtml'))
                require $path . 'header.phtml';

            $path .= strstr($route, '/', true) . '/';
            $route = substr(strstr($route, '/'), 1);
        }

        $path = substr($path, 0, -1);

        // Подключаем сам нужный шаблон
        if (is_readable($path . 'index.phtml'))
            require $path . 'index.phtml';

        // Подключаем footer'ы в обратном порядке
        $route = $getRoute . '/';
        $path = SITEPATH . 'templates/' . $route;

        for ($i = 0; $i < $countRoute; $i++) {
            if (is_readable($path . 'footer.phtml'))
                require $path . 'footer.phtml';

            $path = substr(strrev($path), 1);
            $path = strstr($path, '/');
            $path = strrev($path);
        }
    }

    public function ownHeaderFooter($value = false) {
        $this->ownHeaderFooter = $value;
    }

    public function js($link) {
        if (is_array($link))
            $this->js = array_merge($this->js, $link);
        else
            $this->js[] = $link;
    }

    public function css($link) {
        if (is_array($link))
            $this->css = array_merge($this->css, $link);
        else
            $this->css[] = $link;
    }

    public function title($title) {
        $this->title = '::' . $title;
    }
}
?>
