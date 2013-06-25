<?php
/**
 * Класс, предоставляющий доступ к БД
 * Синглтон
 * @author ismd
 */

class PsDbCantConnectException extends Exception {
    protected $message = 'Не удалось подключиться к БД';
}

class PsDb {

    protected static $_instance;

    /**
     * Объект подключения
     * @var mysqli
     */
    protected $_db;

    /**
     * Подключается к БД
     * @param PsRegistry $registry
     * @throws PsDbCantConnectException
     */
    private function __construct(PsRegistry $registry) {
        $this->_db = new mysqli(
            $registry->config->database->host,
            $registry->config->database->username,
            $registry->config->database->password,
            $registry->config->database->dbname
        );
        
        if ($this->_db->connect_error) {
            throw new PsDbCantConnectException;
        }
    }

    private function __clone() {
    }

    private function __wakeup() {
    }

    /**
     * Возвращает инстанс
     * @param PsRegistry $registry
     * @return PsDb
     * @throws PsDbCantConnectException
     */
    public static function getInstance(PsRegistry $registry) {
        if (is_null(self::$_instance)) {
            self::$_instance = new PsDb($registry);
        }

        return self::$_instance;
    }
    
    /**
     * Возвращает объект подключения к БД
     * @return mysqli
     */
    public function getConnection() {
        return $this->_db;
    }
}
