<?php
/**
 * Класс Registry используется для передачи глобальных значений между отдельными объектами
 * Можно обращаться как к массиву
 */

class Registry implements ArrayAccess {

    private $data = array();

    /**
     * Устанавливаем значение переменной
     *
     * @param string $key - имя
     * @param mixed $var - значение
     */
    public function set($key, $value) {
        $this->data[$key] = $value;
    }

    /**
     * Получаем значение переменной
     *
     * @param string $key - имя
     * @return mixed - значение
     */
    public function get($key) {
        if (!isset($this->data[$key]))
            return null;

        return $this->data[$key];
    }

    /**
     * Удаляем переменную
     *
     * @param string $key - имя
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
        unset($this->data[$offset]);
    }

    public function debug() {
        print_r($this->data);
        die;
    }
}
?>
