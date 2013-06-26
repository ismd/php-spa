<?php
/**
 * @author ismd
 */

class InvalidPropertyException extends Exception {
    protected $message = 'Неверное свойство';
}

abstract class PsObject {

    public function __construct($options = null) {
        // Если передан массив, то сразу инициализируем объект данными
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * Инициализация объекта из массива
     * @param mixed[] $options
     * @return PsObject
     */
    public function setOptions(array $options) {
        foreach ($options as $name => $value) {
            $method = explode('_', $name);

            // ucfirst для всех элементов
            array_walk($method, create_function('&$val', '$val = ucfirst($val);'));

            // Имя метода в стиле camelCase
            $method = 'set' . implode($method);

            if (!method_exists($this, $method)) {
                throw new InvalidPropertyException;
            }

            $this->$method($value);
        }

        return $this;
    }
}
