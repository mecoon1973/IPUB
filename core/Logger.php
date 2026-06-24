<?php

namespace Core;

use Monolog\Logger as LoggerMono;
use Monolog\Handler\StreamHandler;

class Logger extends LoggerMono {

    // public $file_name = "";
    private static array $instances = [];
    /**
     * Debug log thông qua link public/doc/file_name.log => beta.olm.vn/doc/file_name.log
     * @deprecated use getInstance instead
     * @param string $file_name tên file log
     * @param boolean $isPublic true: lưu vào public/doc, false: lưu vào storage/logs
     */
    public function __construct($file_name, $isPublic = false) {
        $path = $isPublic ? public_path("doc/".$file_name.".log") : storage_path("logs/".$file_name.".log");
        $handler = new StreamHandler($path);
        parent::__construct("local", [$handler]);
    }

    /**
     * Get instance of Logger
     * @param string $file_name tên file log
     * @param boolean $isPublic true: lưu vào public/doc, false: lưu vào storage/logs
     * @return self
     */
    public static function getInstance($file_name, $isPublic = false): self {
        $key = $file_name . ($isPublic ? '_public' : '_private');

        if (!isset(self::$instances[$key])) {
            self::$instances[$key] = new self($file_name, $isPublic);
        }

        return self::$instances[$key];
    }

    private function __clone() {}
    public function __wakeup() {}

    public function info($message, array $context = array()): void {
        try {
            parent::info($message, $context);
        } catch (\Throwable $th) {
            // EmailService::getInstance()->sendToNam("Lỗi lưu log", $th->getMessage());
        }
    }

}
