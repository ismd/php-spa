<?php
/**
 * Класс запроса
 * Предоставляет доступ к данным запроса
 * Синглтон
 * @author ismd
 */

class PsRequest {

    protected static $_instance;
    
    private function __construct() {
    }

    private function __clone() {
    }

    private function __wakeup() {
    }

    /**
     * Возвращает инстанс запроса
     * @return PsRequest
     */
    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new PsRequest;
        }

        return self::$_instance;
    }
    
    /**
     * Возвращает post-данные
     * return PsPostRequest
     */
    public function getPost() {
        return PsPostRequest::getInstance();
    }
    
    /**
     * Возвращает true, если переданы данные методом post
     * @return boolean
     */
    public function isPost() {
        return !empty($_POST);
    }
}

/**
 * Данные, переданные методом post
 * Синглтон
 */
class PsPostRequest {

    protected static $_instance;

    private function __construct() {
    }

    private function __clone() {
    }

    private function __wakeup() {
    }

    /**
     * Возвращает инстанс post-запроса
     * @return PsPostRequest
     */
    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new PsPostRequest;
        }

        return self::$_instance;
    }
    
    public function __get($name) {
        return $_POST[$name];
    }
    
    public function __isset($name) {
        return isset($_POST[$name]);
    }
}
