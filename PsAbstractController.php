<?php
/**
 * Родительский класс для всех наших контроллеров
 * @author ismd
 */

abstract class PsAbstractController extends PsObject {

    protected $_registry;

    public function __construct(PsRegistry $registry) {
        $this->_registry = $registry;
    }

    /**
     * Возвращает глобальный регистр
     * @return PsRegistry
     */
    protected function getRegistry() {
        return $this->_registry;
    }

    /**
     * Возвращает объект сессии
     * @return PsSession
     */
    protected function getSession() {
        return PsSession::getInstance();
    }

    /**
     * Возвращает объект представления
     * @return PsView
     */
    protected function getView() {
        return $this->_registry->view;
    }

    /**
     * Возвращает запрос
     * @return PsRequest
     */
    protected function getRequest() {
        return PsRequest::getInstance();
    }
}
