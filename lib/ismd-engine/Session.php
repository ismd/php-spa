<?php
/**
 * Реализация сессии. Автоматическая сериализация
 *
 * @author ismd
 */

class Session {
    
    protected $_data = array();

    public function __construct() {
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

    public function clear() {
        $this->_data = array();
        $_SESSION    = array();
    }
    
    public function __set($name, $value) {
        $this->_data[$name] = $value;
        return $this;
    }
    
    public function __get($name) {
        if (false == isset($this->_data[$name])) {
            return null;
        }
        
        return $this->_data[$name];
    }
    
    public function __isset($name) {
        return isset($this->_data[$name]);
    }
    
    public function __unset($name) {
        if (false == isset($this->_data[$name])) {
            return;
        }
        
        unset($this->_data[$name]);
    }
}
