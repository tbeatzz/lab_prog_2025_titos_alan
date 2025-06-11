<?php
    
    namespace app\libs\database;

    final class Connection{
        private static ?\PDO $conn = null;
        private function __construct(){}
        
        public static function get(): \PDO{
            if(self::$conn == null){
                $conn = new \PDO(DATABASE_DSN, "root", "", array(
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ
                ));
            }
            return $conn
        }
    }
