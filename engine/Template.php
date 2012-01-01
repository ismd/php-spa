<?php
/**
 * Класс для работы с шаблонами
 */

class Template implements ArrayAccess {
    
    private $registry;
    private $data = array();

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
     *
     * @param string $controller - имя контроллера
     * @param array $action - имена действий
     */
    public function show($controller, $action) {
        // Чтобы было удобней обращаться к переменным шаблона из самого шаблона
        $template = $this;

        $f = SITEPATH . 'templates' . DIRSEP;

        // Подключаем главный header
        $filename = $f . 'header.php';

        if (file_exists($filename) && is_readable($filename))
            require $filename;

        // Подключаем header контроллера
        $f .= $controller . DIRSEP;
        $filename = $f . 'header.php';
        
        if (file_exists($filename) && is_readable($filename))
            require $filename;

        // Подключаем последующие header'ы
        $countAction = count($action);
        for ($i = 0; $i < $countAction - 1; $i++) {
            $f .= $action[$i] . DIRSEP;
            $filename = $f . 'header.php';

            if (file_exists($filename) && is_readable($filename))
                require $filename;
        }

        // Подключаем сам файл шаблона
        $filename = $f . $action[$countAction-1] . '.php';

        if (file_exists($filename) && is_readable($filename))
            require $filename;
        else
            die('404 Not Found');

        // Подключаем footer'ы в обратном порядке
        // $i < $countAction + 1, потому что сразу подключаем footer контроллера и главный footer
        for ($i = 0; $i < $countAction + 1; $i++) {
            $filename = $f . 'footer.php';

            if (file_exists($filename) && is_readable($filename))
                require $filename;

            $f = substr(strrev($f), strlen(DIRSEP));
            $f = strstr($f, DIRSEP);
            $f = strrev($f);
        }
    }
}
?>
