<?php  class ComposerAutoloaderInitb5b2f35aa02e2265b79dbcf98bcab5fe{private static $loader;public static function loadClassLoader($class){if('Composer\Autoload\ClassLoader' ===$class){require __DIR__.'/ClassLoader.php';}}public static function getLoader(){if(null !==self::$loader){return self::$loader;}require __DIR__.'/platform_check.php';spl_autoload_register(array('ComposerAutoloaderInitb5b2f35aa02e2265b79dbcf98bcab5fe','loadClassLoader'),true,true);self::$loader=$loader=new \Composer\Autoload\ClassLoader(\dirname(__DIR__));spl_autoload_unregister(array('ComposerAutoloaderInitb5b2f35aa02e2265b79dbcf98bcab5fe','loadClassLoader'));require __DIR__.'/autoload_static.php';call_user_func(\Composer\Autoload\ComposerStaticInitb5b2f35aa02e2265b79dbcf98bcab5fe::getInitializer($loader));$loader->register(true);$includeFiles=\Composer\Autoload\ComposerStaticInitb5b2f35aa02e2265b79dbcf98bcab5fe::$files;foreach($includeFiles as $fileIdentifier =>$file){composerRequireb5b2f35aa02e2265b79dbcf98bcab5fe($fileIdentifier,$file);}return $loader;}}function composerRequireb5b2f35aa02e2265b79dbcf98bcab5fe($fileIdentifier,$file){if(empty($GLOBALS['__composer_autoload_files'][$fileIdentifier])){$GLOBALS['__composer_autoload_files'][$fileIdentifier]=true;require $file;}}