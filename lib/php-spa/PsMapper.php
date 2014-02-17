<?php
/**
 * Родительский класс для mapper'ов
 * @author ismd
 */

abstract class PsMapper extends PsSingleton {

    /**
     * @return PsMapper
     */
    public static function getInstance() {
        return parent::getInstance();
    }
}
