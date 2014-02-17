<?php
/**
 * Класс для логирования
 * @author ismd
 */

class PsLogger extends PsSingleton {

    private $_file;

    /**
     * @global PsRegistry $registry
     */
    protected function __construct() {
        $this->_file = fopen(PsConfig::getInstance()->config->log->filename, 'a');
    }

    public function __destruct() {
        fclose($this->_file);
    }

    /**
     * Осуществляет логирование
     * @param mixed $data
     * @return PsLogger
     */
    public function log($data) {
        if (!is_string($data)) {
            $data = print_r($data, true);
        }

        $data = date('r') . "\t" .  $data . "\n";
        fwrite($this->_file, $data);

        return $this;
    }

    /**
     * @return PsLogger
     */
    public static function getInstance() {
        return parent::getInstance();
    }
}
