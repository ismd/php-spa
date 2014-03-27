<?php
/**
 * Реализация сессии с автоматической сериализацией
 * @author ismd
 */

class PsSession extends PsObject {

    protected function __construct() {
        session_start();

        foreach ($_SESSION as $key => $value) {
            $this->$key = unserialize($value);
        }
    }

    public function __destruct() {
        $_SESSION = [];

        foreach ($this->_data as $key => $value) {
            $_SESSION[$key] = serialize($value);
        }
    }
}
