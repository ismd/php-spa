<?php
/**
 * Автоматически вызывает методы с префиксами set и get
 * Копия PsObject, но с private конструктором
 * Реализация singleton
 * @author ismd
 */

abstract class PsSingleton {

    private static $_instance;

    private function __construct($options = null) {
        // Если передан массив, то сразу инициализируем объект данными
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    private function __clone() {
    }

    private function __wakeup() {
    }

    /**
     * Возвращает инстанс запроса
     * @return $instance
     */
    public static function getInstance($options = null) {
        if (is_null(self::$_instance)) {
            $class = get_called_class();
            self::$_instance = new $class($options);
        }

        return self::$_instance;
    }

    /**
     * Вызывает сеттер
     * Разделитель при вызове -- нижнее подчёркивание
     * Методы в стиле camelCase
     * $object->tmp_var = 'test' вызовет $object->setTmpVar('test')
     * @param string $name Имя метода (в виде свойства)
     * @param mixed $value Будет передано вызываемому методу
     * @return mixed
     * @throws Exception Если не найден метод
     */
    public function __set($name, $value) {
        return $this->invokeMethod('set', $name, $value);
    }

    /**
     * Вызывает геттер
     * Разделитель при вызове -- нижнее подчёркивание
     * Методы в стиле camelCase
     * $object->tmp_var вызовет $object->getTmpVar()
     * @param string $name Имя метода (в виде свойства)
     * @return mixed
     * @throws Exception Если не найден метод
     */
    public function __get($name) {
        return $this->invokeMethod('get', $name);
    }

    /**
     * Вызывает нужный метод
     * @param string $prefix Префикс метода (set или get)
     * @param string $name   Имя метода (в виде свойства)
     * @param mixed $value   Будет передано вызываемому методу
     * @return mixed
     * @throws Exception Если не найден метод
     */
    protected function invokeMethod($prefix, $name, $value = null) {
        $method = explode('_', $name);

        // ucfirst для всех элементов
        array_walk($method, create_function('&$val', '$val = ucfirst($val);'));

        // Имя метода в стиле camelCase
        $method = $prefix . implode($method);

        if (false == method_exists($this, $method)) {
            throw new Exception("Invalid property `$name'");
        }

        return $this->$method($value);
    }

    /**
     * Инициализация объекта из массива
     * @param array $options
     * @return PsObject
     */
    public function setOptions(array $options) {
        foreach ($options as $name => $value) {
            $this->__set($name, $value);
        }

        return $this;
    }
}
