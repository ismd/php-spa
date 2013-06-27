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
     * @var PsSession
     */
    private static $_session;

    public function __construct(PsRegistry $registry) {
        $this->registry = $registry;
        $this->view     = $registry->view;
    }

    /**
     * Возвращает объект сессии
     * @return PsSession
     */
    protected function getSession() {
        if (is_null(self::$_session)) {
            self::$_session = PsSession::getInstance();
        }

        return self::$_session;
    }

    /**
     * Возвращает запрос
     * @return PsRequest
     */
    protected function getRequest() {
        return PsRequest::getInstance();
    }
}
