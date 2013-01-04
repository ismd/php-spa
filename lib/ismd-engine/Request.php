<?php
/**
 * Класс запроса. Доступ к $_POST и $_GET
 * Синглтон
 *
 * @author ismd
 */

class Request extends AbstractModel {

    private static $_instance;
    
    protected $_post;
    protected $_get;

    public function __construct() {
        $this->_post = new PostRequest;
        $this->_get  = new GetRequest;
    }

    private function __clone() {
    }

    private function __wakeup() {
    }

    /**
     * Возвращает инстанс запроса
     * @return Registry
     */
    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new Request;
        }

        return self::$_instance;
    }

    public function getPost() {
        return $this->_post;
    }
    
    public function getGet() {
        return $this->_get;
    }
    
    /**
     * Возвращает true, если переданы данные методом post
     */
    public function isPost() {
        return !empty($_POST);
    }
}

/**
 * Общий
 */
interface CommonRequest {
    public function __get($name);
    public function __isset($name);
}

/**
 * Данные, переданные методом post
 */
class PostRequest implements CommonRequest {
    
    public function __get($name) {
        if (false == isset($_POST[$name])) {
            return null;
        }
        
        return $_POST[$name];
    }
    
    public function __isset($name) {
        return isset($_POST[$name]);
    }
}

/**
 * Данные, переданные методом get
 */
class GetRequest implements CommonRequest {
    
    public function __get($name) {
        if (false == isset($_GET[$name])) {
            return null;
        }
        
        return $_GET[$name];
    }
    
    public function __isset($name) {
        return isset($_GET[$name]);
    }
}
