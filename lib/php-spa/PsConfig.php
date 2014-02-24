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
    protected $_config;

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

        $this->_config = (object)$config;
    }

    public function __get($name) {
        return new PsConfigSection(isset($this->_config->$name) ? (object)$this->_config->$name : null);
    }

    /**
     * @return PsConfig
     */
    public static function getInstance() {
        return parent::getInstance();
    }
}

class PsConfigSection {

    protected $_value;

    public function __construct(object $value) {
        $this->_value = $value;
    }

    public function __get($name) {
        if (is_null($this->_value) || !isset($this->_value->$name)) {
            return null;
        }

        return $this->_value->$name;
    }
}
