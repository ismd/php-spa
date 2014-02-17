<?php
/**
 * Реализация сессии с автоматической сериализацией
 * @author ismd
 */

class PsSession extends PsSingleton {

    /**
     * Данные в сессии
     * @var mixed[]
     */
    private $_data = [];

    /**
     * Очищает сессию
     * @return PsSession
     */
    public function clear() {
        $this->_data = [];
        return $this;
    }

    protected function __construct() {
        session_start();

        foreach ($_SESSION as $key => $value) {
            $this->_data[$key] = unserialize($value);
        }
    }

    public function __destruct() {
        $_SESSION = [];

        foreach ($this->_data as $key => $value) {
            $_SESSION[$key] = serialize($value);
        }
    }

    public function __set($name, $value) {
        $this->_data[$name] = $value;
        return $this;
    }

    public function __get($name) {
        return $this->_data[$name];
    }

    public function __isset($name) {
        return isset($this->_data[$name]);
    }

    public function __unset($name) {
        unset($this->_data[$name]);
    }

    /**
     * @return PsSession
     */
    public static function getInstance() {
        return parent::getInstance();
    }
}
