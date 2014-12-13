<?php

/**
 * Родительский класс для всех контроллеров
 * @author ismd
 */
abstract class PsController {

    /**
     * @var PsRegistry
     */
    protected $registry;

    /**
     * @var PsView
     */
    protected $view;

    /**
     * @param PsRegistry $registry
     */
    public function __construct(PsRegistry $registry) {
        $this->registry = $registry;
        $this->view     = $registry->view;
    }

    /**
     * Возвращает объект сессии
     * @return PsSession
     */
    protected function getSession() {
        if (is_null($this->registry->_session)) {
            $this->registry->_session = new PsSession;
        }

        return $this->registry->_session;
    }

    /**
     * Возвращает запрос
     * @return PsRequest
     */
    protected function getRequest() {
        return PsRequest::getInstance();
    }

    /**
     * Возвращает аргументы, переданные в url
     * @return mixed[]
     */
    protected function getArgs() {
        return $this->registry->router->getArgs();
    }
}
