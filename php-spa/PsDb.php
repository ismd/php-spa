<?php
/**
 * Класс, предоставляющий доступ к БД
 * @author ismd
 */

class DbCantConnectException extends Exception {
    protected $message = 'Не удалось подключиться к БД';
}

class PsDb extends PsSingleton {

    /**
     * Объект подключения
     * @var mysqli
     */
    public $db;

    /**
     * Подключается к БД
     * @throws DbCantConnectException
     */
    protected function __construct() {
        $config = PsConfig::getInstance()->config->database;

        $this->db = new mysqli(
            $config->host,
            $config->username,
            $config->password,
            $config->dbname
        );

        if ($this->db->connect_error) {
            throw new DbCantConnectException;
        }

        $this->db->query('SET NAMES UTF8');
    }

    public function __destruct() {
        $this->db->close();
    }

    /**
     * @return PsDb
     */
    public static function getInstance() {
        return parent::getInstance();
    }
}
