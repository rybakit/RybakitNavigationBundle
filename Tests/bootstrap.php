<?php

spl_autoload_register(function ($class) {
    if (0 === strpos(ltrim($class, '/'), 'Rybakit\\Bundle\\NavigationBundle')) {
        $file = __DIR__.'/../'.substr(str_replace('\\', '/', $class), strlen('Rybakit\\Bundle\\NavigationBundle')).'.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }
});

if (file_exists($loader = __DIR__.'/../vendor/autoload.php')) {
    require_once $loader;
}
