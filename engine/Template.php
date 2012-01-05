<?php
/**
 * Класс для работы с шаблонами
 */

class Template implements ArrayAccess {

    private $registry;
    private $data = array();
    private $ownHeaderFooter = false;

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

        $getRoute = (empty($_GET['route'])) ? 'index' : str_replace('/', DIRSEP, trim($_GET['route'], '/'));

        if ($this->ownHeaderFooter) {
            $path = SITEPATH . 'templates' . DIRSEP .  $getRoute . DIRSEP;
            $filename = substr($path, 0, -strlen(DIRSEP)) . '.php';
            $path1 = strrev(strstr(strrev($filename), DIRSEP));

            if (is_readable($filename)) {
                if (is_readable($path1 . 'header.php'))
                    require $path1 . 'header.php';

                require $filename;

                if (is_readable($path1 . 'footer.php'))
                    require $path1 . 'footer.php';
            }

            if (is_readable($path . 'header.php'))
                require $path . 'header.php';

            if (is_readable($path . 'index.php'))
                require $path . 'index.php';

            if (is_readable($path . 'footer.php'))
                require $path . 'footer.php';

            return;
        }

        $route = $getRoute . DIRSEP;
        $path = SITEPATH . 'templates' . DIRSEP;
        $countRoute = substr_count($route, DIRSEP) + 1;

        // Подключаем последовательно header'ы
        for ($i = 0; $i < $countRoute; $i++) {
            if (is_readable($path . 'header.php'))
                require $path . 'header.php';

            $path .= strstr($route, DIRSEP, true) . DIRSEP;
            $route = substr(strstr($route, DIRSEP), strlen(DIRSEP));
        }

        $path = substr($path, 0, -strlen(DIRSEP));

        // Подключаем сам нужный шаблон
        if (is_readable($path . 'index.php'))
            require $path . 'index.php';

        // Подключаем footer'ы в обратном порядке
        $route = $getRoute . DIRSEP;
        $path = SITEPATH . 'templates' . DIRSEP . $route;

        for ($i = 0; $i < $countRoute; $i++) {
            if (is_readable($path . 'footer.php'))
                require $path . 'footer.php';

            $path = substr(strrev($path), strlen(DIRSEP));
            $path = strstr($path, DIRSEP);
            $path = strrev($path);
        }
    }

    public function ownHeaderFooter($value = false) {
        $this->ownHeaderFooter = $value;
    }
}
?>
