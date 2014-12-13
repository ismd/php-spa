<?php

/**
 * Реализация singleton
 * @author ismd
 */
abstract class PsSingleton {

    /**
     * @var object[] Массив созданных объектов
     */
    private static $_instances = [];

    private function __construct() {
    }

    private function __clone() {
    }

    private function __wakeup() {
    }

    /**
     * Возвращает инстанс запроса
     * @return PsSingleton
     */
    public static function getInstance() {
        $class = get_called_class();

        if (!isset(self::$_instances[$class])) {
            self::$_instances[$class] = new $class;
        }

        return self::$_instances[$class];
    }
}
