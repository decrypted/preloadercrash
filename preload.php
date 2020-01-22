<?php

echo "Preloading ... \n";
error_reporting(-1);
echo "Current Directory: ".getcwd()."\n";

require_once __DIR__ . '/preloader.php';
(new CW_Preload())
    ->paths(__DIR__.'/vendor/predis')
    ->load();

echo "Preload done\n";
