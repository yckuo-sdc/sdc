<?php
if(!defined("SITE_ROOT"))	define("SITE_ROOT", "/var/www/html/sdc");
require SITE_ROOT.'/libraries/database_access_object_mysqli.php';
require SITE_ROOT.'/config/MySQL.php';

class DatabaseMysqli {
  
    private static $instance;
    private function __construct() {
        // 使用 private 建構子避免在外面被意外地初始化
    }

    private static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new DatabaseAccessObjectMysqli(
              MySQL::ADDRESS,
              MySQL::USERNAME,
              MySQL::PASSWORD,
              MySQL::DATABASE
            );
        }
    }

    public static function get() {
        self::getInstance();
        if (isset(self::$instance)) {
            return self::$instance;
        } else {
            return NULL;
        }
    }

    public static function unlinkDAO() {
        if (isset(self::$instance)) {
            self::$instance = null; // 會自動執行解構子 close link
        }
    }
}
