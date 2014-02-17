<?php
/**
 * Класс, предоставляющий доступ к конфигам
 * @author ismd
 */

class PsConfig extends PsSingleton {

    /**
     * Путь к главному конфигурационному файлу
     * @var string
     */
    const FILENAME = '/configs/application.ini';

    /**
     * Объект конфига
     * @var object
     */
    public $config;

    /**
     * Читает конфигурационный файл
     * @throws Exception
     */
    protected function __construct() {
        $filename = APPLICATION_PATH . self::FILENAME;

        if (!is_readable($filename)) {
            throw new Exception("Can't read config file");
        }

        $config = parse_ini_file($filename, true);

        if (!$config) {
            throw new Exception("Can't read config file");
        }

        // Преобразуем все элементы к объектам
        array_walk($config, function($val) {
            return (object)$val;
        });

        $this->config = (object)$config;
    }

    /**
     * @return PsConfig
     */
    public static function getInstance() {
        return parent::getInstance();
    }
}
