<?php
/**
 * Класс для логирования. Синглтон
 *
 * @author ismd
 */
class Logger {

    private static $_instance;
    private $_logFile;

    private function __construct($filename) {
        $this->_logFile = $filename;
    }

    private function __clone() {
    }

    private function __wakeup() {
    }

    /**
     * Возвращает инстанс логгера
     *
     * @global Registry $registry
     * @return Logger
     */
    public static function getInstance() {
        if (is_null(self::$_instance)) {
            global $registry;
            self::$_instance = new Logger($registry->config->log->filename);
        }

        return self::$_instance;
    }

    public function log($text) {
        $file = fopen($this->_logFile, 'a');

        if (!is_string($text)) {
            $text = print_r($text, true);
        }

        $text = date('r') . "\t" .  $text . "\n";

        fwrite($file, $text);
        fclose($file);
    }
}
