<?php
/**
 * Родительский класс для mapper'ов с подключением к БД
 * @author ismd
 */

abstract class PsAbstractDbMapper extends PsObject {

    protected static $_db;
    
    /**
     * Подключается к БД
     * @global type $registry
     * @throws PsDbCantConnectException
     */
    public function __construct() {
        if (is_null($this->_db)) {
            global $registry;

            $registry->db = PsDb::getInstance($registry)->getConnection();
            $this->db     = $registry->db;
        }
    }
    
    protected function setDb($value) {
        $this->_db = $value;
    }
    
    /**
     * @return mysqli
     */
    public function getDb() {
        return $this->_db;
    }
}
