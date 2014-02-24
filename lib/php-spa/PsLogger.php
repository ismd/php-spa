<?php
/**
 * Класс для логирования
 * @author ismd
 */

class PsLogger extends PsSingleton {

    private $_file;

    /**
     * @global PsRegistry $registry
     * @throws Exception
     */
    protected function __construct() {
        $filename = PsConfig::getInstance()->log->filename;

        if (is_null($filename)) {
            throw new Exception('Log filename is empty');
        }

        $this->_file = fopen($filename, 'a');

        if (!$this->_file) {
            throw new Exception("Can't open log file");
        }
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
