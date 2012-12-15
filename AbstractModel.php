<?php
/**
 * Родительский класс для моделей
 * Автоматически вызывает методы с префиксами set и get
 *
 * @author ismd
 */

abstract class AbstractModel {

    public function __construct(array $options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value) {
        $method = explode('_', $name);

        foreach ($method as $i => $m) {
            $method[$i] = ucfirst($m);
        }

        $method = 'set' . implode('', $method);

        if ($name == 'mapper' || !method_exists($this, $method)) {
            throw new Exception('Invalid model property `' . $name . "'");
        }

        $this->$method($value);
    }

    public function __get($name) {
        $method = explode('_', $name);

        foreach ($method as $i => $m) {
            $method[$i] = ucfirst($m);
        }

        $method = 'get' . implode('', $method);

        if ($name == 'mapper' || !method_exists($this, $method)) {
            throw new Exception('Invalid model property `' . $name . "'");
        }

        return $this->$method();
    }

    public function setOptions(array $options) {
        foreach ($options as $name => $value) {
            $this->__set($name, $value);
        }

        return $this;
    }
}
