<?php

    namespace backend\helpers;
    use Dotenv\Dotenv;

    class ENVManager{
        private static $dotENV;
        public static function LoadENV(){
            if(!self::$dotENV){
                self::$dotENV = Dotenv::createImmutable(__DIR__ .'/../../');
                self::$dotENV->load();
            }
            return self::$dotENV;
        }

        public static function GetENV($key){
            self::LoadENV();
            return $_ENV[$key];
        }
    }

?>