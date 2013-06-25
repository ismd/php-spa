<?php
/**
 * Класс, предоставляющий доступ к конфигам
 * Синглтон
 * @author ismd
 */

class PsConfigCantReadException extends Exception {
    protected $message = 'Не удалось прочитать конфигурационный файл';
}

class PsConfig {

    protected static $_instance;

    /**
     * Путь к главному конфигурационному файлу
     * @var string
     */
    protected $_filename = 'application/configs/application.ini';

    /**
     * Объект конфига
     */
    protected $_config;

    /**
     * Читает конфигурационный файл
     * @throws PsConfigCantReadException
     */
    private function __construct() {
        $filename = SITEPATH . $this->_filename;

        if (false == is_readable($filename)) {
            throw new PsConfigCantReadException;
        }

        $this->_config = parse_ini_file($filename, true);

        if (false == $this->_config) {
            throw new PsConfigCantReadException;
        }

        // Преобразуем все элементы к объектам
        array_walk($this->_config, create_function('&$val', '$val = (object)$val;'));

        $this->_config = (object)$this->_config;
    }

    private function __clone() {
    }

    private function __wakeup() {
    }

    /**
     * Возвращает инстанс
     * @return PsConfig
     * @throws PsConfigCantReadException
     */
    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new PsConfig();
        }

        return self::$_instance;
    }

    /**
     * Возвращает объект конфига
     * @return object
     */
    public function getConfig() {
        return $this->_config;
    }
}
