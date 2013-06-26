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

    public function __construct(PsRegistry $registry) {
        $this->registry = $registry;
        $this->view      = $registry->view;
    }

    /**
     * Возвращает объект сессии
     * @return PsSession
     */
    protected function getSession() {
        return PsSession::getInstance();
    }

    /**
     * Возвращает запрос
     * @return PsRequest
     */
    protected function getRequest() {
        return PsRequest::getInstance();
    }
}
