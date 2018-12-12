<?php
class ClassLoader {

    public static $classMap;
    public static $addMap = array();
    public static $dir = [
            'vendor',
            'databases',
    ];

    public static function addClassMap($class = array()){
            self::$addMap = array_merge(self::$addMap, $class);
        }

    public static function autoload($className){

            self::$classMap = array_merge(require(__DIR__ . '/classes.php'), self::$addMap);

            if (isset(self::$classMap[$className])) {
                $filename = self::$classMap[$className];
                include_once @'/home/vagrant/code/blog/' . $filename;

            } else {
                self::library($className);
            }

            if (!class_exists($className, false) && !interface_exists($className, false) && !trait_exists($className, false)) {
                throw new Exception('Невозможно найти класс '.$className);
            }
    }

    public static function library($className){
            foreach (self::$dir as $d){
                $filename = ROOT_DIR . $d . '/'. $className . ".php";
                if (is_readable($filename)) {
                    require_once $filename;
                }
            }
        }

}
