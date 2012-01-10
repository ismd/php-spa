<?php
/**
 * Родительский класс для всех наших контроллеров
 */

abstract class DefaultController {
    
    protected $registry;
    protected $template;
    protected $model;

    public function __construct($registry, $model=null) {
        $this->registry = $registry;
        $this->template = $registry['template'];
        $this->model = $model;
    }

    /**
     * Обязательное действие index у всех контроллеров (страница по-умолчанию)
     */
    abstract public function index($args = null);
}
?>
