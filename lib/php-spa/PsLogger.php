<?php
/**
 * Класс для логирования
 * Синглтон
 * @author ismd
 */

class PsLogger {

    protected static $_instance;

    /**
     * Путь к файлу, в который пишем лог
     * @var string
     */
    protected $_filename;

    private function __construct($filename) {
        $this->_filename = $filename;
    }

    private function __clone() {
    }

    private function __wakeup() {
    }

    /**
     * Возвращает инстанс логгера
     * @param PsRegistry $registry
     * @return PsLogger
     */
    public static function getInstance(PsRegistry $registry) {
        if (is_null(self::$_instance)) {
            self::$_instance = new PsLogger($registry->config->log->filename);
        }

        return self::$_instance;
    }

    /**
     * Осуществляет логирование
     * @param string $text
     * @return PsLogger
     */
    public function log($data) {
        $file = fopen($this->_filename, 'a');

        if (!is_string($data)) {
            $data = print_r($data, true);
        }

        $data = date('r') . "\t" .  $data . "\n";
        fwrite($file, $data);
        fclose($file);

        return $this;
    }
}
