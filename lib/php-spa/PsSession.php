<?php
/**
 * Реализация сессии с автоматической сериализацией
 * Синглтон
 * @author ismd
 */

class PsSession {

    protected static $_instance;

    /**
     * Данные в сессии
     * @var array
     */
    protected $_data = array();

    /**
     * Очищает сессию
     * @return PsSession
     */
    public function clear() {
        $this->_data = array();
        return $this;
    }

    private function __construct() {
        session_start();

        foreach ($_SESSION as $key => $value) {
            $this->_data[$key] = unserialize($value);
        }
    }

    public function __destruct() {
        $_SESSION = array();
        
        foreach ($this->_data as $key => $value) {
            $_SESSION[$key] = serialize($value);
        }
    }

    private function __clone() {
    }

    private function __wakeup() {
    }

    /**
     * Возвращает инстанс сессии
     * @return PsSession
     */
    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new PsSession;
        }

        return self::$_instance;
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
}
