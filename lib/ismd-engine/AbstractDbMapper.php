<?php
/**
 * Родительский класс для mapper'ов с подключением к БД
 * 
 * @author ismd
 */

abstract class AbstractDbMapper {

    protected $registry;
    protected $db;

    public function __construct() {
        global $registry;

        $this->registry = $registry;
        $this->db       = $registry->db;
    }
}
