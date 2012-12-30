<?php
/**
 * Родительский класс для всех наших контроллеров
 *
 * @author ismd
 */

abstract class AbstractController {

    protected $registry;
    protected $session;
    protected $view;
    protected $request;

    public function __construct($registry) {
        $this->registry = $registry;
        $this->session  = $registry->session;
        $this->view     = $registry->view;
        $this->request  = $registry->request;
    }

    /**
     * Обязательное действие index у всех контроллеров (страница по-умолчанию)
     */
    abstract public function index();

    /**
     * Перенаправляет на другую страницу
     *
     * @param string $url
     */
    protected function redirect($url) {
        header("Location: $url");
        die;
    }
}
