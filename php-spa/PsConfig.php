<?php
/**
 * Класс, предоставляющий доступ к конфигам
 * @author ismd
 */

class ConfigCantReadException extends Exception {
    protected $message = 'Не удалось прочитать конфигурационный файл';
}

class PsConfig extends PsSingleton {

    /**
     * Путь к главному конфигурационному файлу
     * @var string
     */
    private $_filename = '/configs/application.ini';

    /**
     * Объект конфига
     * @var object
     */
    public $config;

    /**
     * Читает конфигурационный файл
     * @throws ConfigCantReadException
     */
    protected function __construct() {
        $filename = APPLICATION_PATH . $this->_filename;

        if (!is_readable($filename)) {
            throw new ConfigCantReadException;
        }

        $config = parse_ini_file($filename, true);

        if (!$config) {
            throw new ConfigCantReadException;
        }

        // Преобразуем все элементы к объектам
        array_walk($config, create_function('&$val', '$val = (object)$val;'));

        $this->config = (object)$config;
    }

    /**
     * @return PsConfig
     */
    public static function getInstance() {
        return parent::getInstance();
    }
}
