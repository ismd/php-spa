<?php
/**
 * Родительский класс для всех наших моделей
 */

abstract class DefaultModel {

    protected $registry;

    public function  __construct($registry) {
        $this->registry = $registry;
    }
}
?>
