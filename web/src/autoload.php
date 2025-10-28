<?php
function autoload($className): void
{
    $baseDir = dirname(__DIR__);

    $file = $baseDir . '/src/' . str_replace('\\', '/', $className) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
}

spl_autoload_register('autoload');
