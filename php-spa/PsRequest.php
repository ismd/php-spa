<?php
/**
 * Предоставляет доступ к данным запроса
 * @author ismd
 */

class PsRequest extends PsSingleton {

    private $_post;

    /**
     * Возвращает post-данные
     * return PsPostRequest
     */
    public function getPost() {
        if (null == $this->_post) {
            $this->_post = new PsPostRequest;
        }

        return $this->_post;
    }

    /**
     * Возвращает true, если переданы данные методом post
     * @return boolean
     */
    public function isPost() {
        return !empty($_POST);
    }

    /**
     * @return PsRequest
     */
    public function getInstance() {
        return parent::getInstance();
    }
}

/**
 * Данные, переданные методом post
 */
class PsPostRequest {

    public function __get($name) {
        return $_POST[$name];
    }

    public function __isset($name) {
        return isset($_POST[$name]);
    }
}
