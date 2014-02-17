<?php
/**
 * Родительский класс для mapper'ов с подключением к БД
 * @author ismd
 */

abstract class PsDbMapper extends PsMapper {

    /**
     * @var mysqli
     */
    protected $db;

    /**
     * Подключается к БД
     * @throws Exception
     */
    protected function __construct() {
        $this->db = PsDb::getInstance()->db;
    }

    /**
     * @return PsDbMapper
     */
    public static function getInstance() {
        return parent::getInstance();
    }
}
